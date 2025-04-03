<?php

namespace App\Http\Controllers\Api;

use App\Events\NewMessageSent;
use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    // Create a new chat
    public function createChat(Request $request)
    {
        $request->validate([
            'farmer_id' => 'required|exists:users,id',
            'officer_id' => 'required|exists:users,id',
        ]);

        $chat = Chat::firstOrCreate([
            'farmer_id' => $request->farmer_id,
            'officer_id' => $request->officer_id,
        ]);

        return response()->json($chat, 201);
    }

    // Get messages for a chat
    public function getMessages($chatId)
    {
        $messages = Message::where('chat_id', $chatId)->with('sender')->get();
        return response()->json($messages);
    }

    // Send a message
    public function sendMessage(Request $request, $chatId)
    {
        $request->validate([
            'sender_id' => 'required|exists:users,id',
            'message' => 'required|string',
        ]);

        $message = Message::create([
            'chat_id' => $chatId,
            'sender_id' => $request->sender_id,
            'message' => $request->message,
        ]);

        // Broadcast the event
        broadcast(new NewMessageSent($message))->toOthers();

        return response()->json($message, 201);
    }
}
