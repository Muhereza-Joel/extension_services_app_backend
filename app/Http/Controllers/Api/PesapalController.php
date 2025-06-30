<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PesapalController extends Controller
{
    private $client;
    private $baseUrl;

    public function __construct()
    {
        $this->client = new Client([
            'verify' => env('APP_ENV') === 'local' ? false : true, // Disable SSL verification in local environment
        ]);
        $this->baseUrl = env('PESAPAL_ENV') === 'sandbox'
            ? 'https://cybqa.pesapal.com/pesapalv3'
            : 'https://cybqa.pesapal.com/pesapalv3';
    }

    public function getAccessToken()
    {
        $response = $this->client->post("$this->baseUrl/api/Auth/RequestToken", [
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'consumer_key' => env('PESAPAL_CONSUMER_KEY'),
                'consumer_secret' => env('PESAPAL_CONSUMER_SECRET'),
            ],
        ]);

        return json_decode($response->getBody(), true)['token'];
    }

    public function registerIPN()
    {
        $accessToken = $this->getAccessToken();

        $response = $this->client->post("$this->baseUrl/api/URLSetup/RegisterIPN", [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'url' => env('PESAPAL_CALLBACK_URL'), // Your IPN callback URL
                'ipn_notification_type' => 'GET', // Use 'GET' or 'POST' based on your needs
            ],
        ]);

        $responseData = json_decode($response->getBody(), true);

        // Save the IPN ID to the database or cache for later use
        return $responseData['ipn_id'];
    }

    public function createPayment(Request $request)
    {
        $request->validate([
            'ticket_id' => 'required|exists:tickets,id',
            'amount' => 'required|numeric',
            'email' => 'required|email',
            'phone_number' => 'required|string',
        ]);

        $accessToken = $this->getAccessToken();
        $ipnId = $this->registerIPN();
        $reference_number = 'xTendat-ticket' . mt_rand(10000000, 99999999) . uniqid();

        $response = $this->client->post("$this->baseUrl/api/Transactions/SubmitOrderRequest", [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'id' => $reference_number, // Unique order ID
                'currency' => 'UGX',
                // 'amount' => $request->amount,
                'amount' => 500,
                'description' => 'Payment for meeting ticket',
                'callback_url' => env('PESAPAL_CALLBACK_URL'), // Callback URL
                'notification_id' => $ipnId,
                'billing_address' => [
                    'email_address' => $request->email,
                    'phone_number' => $request->phone_number,
                ],
            ],
        ]);

        //first update the ticket with order tracking id and reference number
        $ticket = \App\Models\Ticket::findOrFail($request->ticket_id);
        $ticket->order_tracking_id = json_decode($response->getBody(), true)['order_tracking_id'];
        $ticket->reference_number = $reference_number;
        $ticket->save();

        return response()->json(json_decode($response->getBody(), true));
    }


    public function handleIPN(string $OrderTrackingId)
    {
        try {
            $accessToken = $this->getAccessToken();
            $response = Http::withoutVerifying()
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                ])
                ->get("https://cybqa.pesapal.com/pesapalv3/api/Transactions/GetTransactionStatus?orderTrackingId={$OrderTrackingId}");

            if ($response->successful()) {
                $data = $response->json();
                $status = $data['payment_status_description'] ?? 'Unknown';


                if ($data) {
                    // Only proceed if payment is COMPLETED
                    if ($status === 'Completed') {
                        DB::transaction(function () use ($data, $status, $OrderTrackingId) {
                            $ticket = Ticket::where('reference_number', $data['merchant_reference'])->firstOrFail();

                            $ticket->update([
                                'status'         => 'paid',
                            ]);
                        });
                    } else {
                        // Optionally log or notify if payment is not completed
                        Log::info("Payment status not completed: {$status}");
                    }
                } else {
                    Log::warning("No data returned from PesaPal for tracking ID: {$OrderTrackingId}");
                }
            } else {
                throw new \Exception('Failed to check payment status from PesaPal');
            }
        } catch (\Exception $e) {
            Log::error('Error checking payment status', ['error' => $e->getMessage()]);
        }
    }
}
