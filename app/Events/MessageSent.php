<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    public Message $message;

    public function __construct(Message $message)
    {
        $this->message = $message->load(['attachments']);
    }

    public function broadcastOn()
    {
        return new Channel('conversation.' . ($this->message->parent_id ?? $this->message->id));
    }

    public function broadcastAs()
    {
        return 'message.sent';
    }

    public function broadcastWith()
    {
        return [
            'conversation_id' => $this->message->parent_id ?? $this->message->id,
            'parent_id' => $this->message->parent_id,
            'message' => [
                'id'        => $this->message->id,
                'senderId'  => $this->message->sender_id,
                'body'      => $this->message->body,
                'time'      => $this->message->created_at,
                'attachments' => $this->message->attachments->map(fn ($a) => [
                    'id'   => $a->id,
                    'name' => $a->original_name,
                    'url'  => asset('storage/'.$a->path),
                    'size' => $a->size,
                    'mime' => $a->mime_type,
                ])->toArray()
            ]
        ];
    }
}

