<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Message;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    // fetch all previous messages when user opens chat
    public function index()
    {
        $messages = Message::with('user')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($msg) {
                return [
                    'id'         => $msg->id,
                    'message'    => $msg->message,
                    'user_id'    => $msg->user_id,
                    'user_name'  => $msg->user->name,
                    'created_at' => $msg->created_at->format('h:i A'),
                ];
            });

        return response()->json($messages);
    }

    // send a new message
    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:500',
        ]);

        $message = Message::create([
            'user_id' => auth()->id(),
            'message' => $request->message,
        ]);

        $message->load('user');

        // fire the event → Reverb broadcasts it to everyone
        broadcast(new MessageSent($message))->toOthers();

        return response()->json([
            'id'         => $message->id,
            'message'    => $message->message,
            'user_id'    => $message->user_id,
            'user_name'  => $message->user->name,
            'created_at' => $message->created_at->format('h:i A'),
        ]);
    }
}

// ## What each function does:

// ### `index()` — loads old messages
// ```
// User opens chat page
//         ↓
// Vue calls GET /api/chat
//         ↓
// Laravel returns all previous messages from DB
//         ↓
// Vue displays them so user sees chat history
// ```

// ### `store()` — sends a new message
// ```
// User types message and hits send
//         ↓
// Vue calls POST /api/chat
//         ↓
// Laravel validates & saves to DB
//         ↓
// broadcast(new MessageSent()) fires ← this is the WebSocket part
//         ↓
// Reverb pushes it to everyone listening
//         ↓
// Returns the message back to the sender too
