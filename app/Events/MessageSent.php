<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function broadcastOn()
    {
        // Broadcast to a private channel for the receiver
        return new PrivateChannel('messages.' . $this->message->receiver_id);
    }

    public function broadcastWith()
    {
        // Data to send with the event
        return ['message' => $this->message];
    }
}
