<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ExtensionService;
use App\Models\Meeting;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ExtensionServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            "extension_services" => ExtensionService::latest()->get()
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    public function storeTicket(Request $request)
    {
        $request->validate([
            'meeting_id' => 'required|exists:meetings,id',
            'price' => 'required|numeric|min:0',
            'attendee_id' => 'required|exists:users,id',
        ]);

        // Check if the user already has a ticket for this meeting
        $existingTicket = auth()->user()->tickets()
            ->where('meeting_id', $request->meeting_id)
            ->first();

        if ($existingTicket) {
            return response()->json([
                'message' => 'You have already created a ticket for this meeting.',
            ], 409); // 409 Conflict status code
        }

        // Create the ticket if the user doesn't already have one for this meeting
        $ticket = auth()->user()->tickets()->create([
            'meeting_id' => $request->meeting_id,
            'price' => $request->price,
            'attendee_id' => $request->attendee_id,
        ]);

        return response()->json([
            "ticket" => $ticket
        ], 201); // 201 Created status code
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json([
            "extension_service" => ExtensionService::findOrFail($id)
        ]);
    }

    public function serviceMeetings(string $id)
    {
        $extensionService = ExtensionService::findOrFail($id);

        $meetings = $extensionService->meetings()
            ->orderBy('date', 'asc')
            ->orderBy('time', 'asc')
            ->get()
            ->map(function ($meeting) {
                $date = Carbon::parse($meeting->date);
                return [
                    "id" => $meeting->id,
                    "title" => $meeting->title,
                    "venue" => $meeting->venue,
                    "description" => $meeting->description,
                    "presenter" => $meeting->presenter,
                    "status" => $meeting->status,
                    "date" => $date->format('l, F jS, Y'), // Example: Friday, April 4th, 2025
                    "time" => Carbon::parse($meeting->time)->format('g:i A'), // Example: 2:00 AM
                ];
            });

        return response()->json([
            "meetings" => $meetings
        ]);
    }


    public function allMeetings()
    {
        $meetings = Meeting::query()
            ->orderBy('date', 'asc')
            ->orderBy('time', 'asc')
            ->get()
            ->map(function ($meeting) {
                $date = Carbon::parse($meeting->date);
                return [
                    "id" => $meeting->id,
                    "title" => $meeting->title,
                    "venue" => $meeting->venue,
                    "description" => $meeting->description,
                    "presenter" => $meeting->presenter,
                    "status" => $meeting->status,
                    "date" => $date->format('l, F jS, Y'), // Example: Friday, April 4th, 2025
                    "time" => Carbon::parse($meeting->time)->format('g:i A'), // Example: 2:00 AM
                ];
            });

        return response()->json([
            "meetings" => $meetings
        ]);
    }

    public function showMeeting(string $id)
    {
        $meeting = Meeting::with('tickets.attendee')->findOrFail($id);

        $date = Carbon::parse($meeting->date);

        return response()->json([
            "meeting" => [
                "id" => $meeting->id,
                "title" => $meeting->title,
                "venue" => $meeting->venue,
                "description" => $meeting->description,
                "presenter" => $meeting->presenter,
                "price" => $meeting->price,
                "status" => $meeting->status,
                "date" => $date->format('l, F jS, Y'), // Example: Friday, April 4th, 2025
                "time" => Carbon::parse($meeting->time)->format('g:i A'), // Example: 2:00 AM
                "tickets" => $meeting->tickets->map(function ($ticket) {
                    return [
                        "id" => $ticket->id,
                        "ticket_number" => $ticket->ticket_number,
                        "price" => $ticket->price,
                        "status" => $ticket->status,
                        "attendee" => $ticket->attendee ? [
                            "id" => $ticket->attendee->id,
                            "name" => $ticket->attendee->name,
                            "email" => $ticket->attendee->email,
                        ] : null, // Null if no attendee is assigned
                    ];
                }),
            ]
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
