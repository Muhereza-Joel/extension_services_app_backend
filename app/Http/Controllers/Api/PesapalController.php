<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

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
            : 'https://pay.pesapal.com/v3';
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

        $response = $this->client->post("$this->baseUrl/api/Transactions/SubmitOrderRequest", [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'id' => uniqid(), // Unique order ID
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

        return response()->json(json_decode($response->getBody(), true));
    }

    //     public function handleIPN(Request $request)
    // {
    //     $orderTrackingId = $request->input('OrderTrackingId');
    //     $paymentStatus = $request->input('PaymentStatus');

    //     // Update payment status in the database
    //     Payment::where('order_tracking_id', $orderTrackingId)->update([
    //         'status' => $paymentStatus,
    //     ]);

    //     return response()->json(['message' => 'IPN received']);
    // }
}
