<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Message $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('chat');
    }

    public function broadcastWith(): array
    {
        return [
            'id'         => $this->message->id,
            'message'    => $this->message->message,
            'user_id'    => $this->message->user_id,
            'user_name'  => $this->message->user->name,
            'created_at' => $this->message->created_at->format('h:i A'),
        ];
    }
}




## What each part does:

// | Part | What it does |
// |---|---|
// | `implements ShouldBroadcast` | Tells Laravel this event goes over WebSocket |
// | `public Message $message` | The message data we want to broadcast |
// | `broadcastOn()` | Which channel to broadcast on — we use `chat` |
// | `broadcastWith()` | Exactly what data gets sent to Vue |
// | `user->name` | We include the sender's name so Vue can display it |

// ---

// ## One important thing — the `chat` channel:
// ```
// Laravel fires MessageSent
//         ↓
// Reverb broadcasts it on the 'chat' channel
//         ↓
// Every Vue user listening to 'chat' channel receives it instantly
