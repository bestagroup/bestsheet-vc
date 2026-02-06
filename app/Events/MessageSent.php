<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Message $message;

    /**
     * Create a new event instance.
     */
    public function __construct(Message $message)
    {
        // بارگذاری پیوست‌ها
        $this->message = $message->load('attachments');
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): Channel|array
    {
        // کانال خصوصی بر اساس پیام اصلی یا parent_id
        return new PrivateChannel('conversation.' . ($this->message->parent_id ?? $this->message->id));
    }

    /**
     * نام event در JS
     */
    public function broadcastAs(): string
    {
        return 'message.sent';
    }

    /**
     * داده‌هایی که broadcast می‌شوند
     */
    public function broadcastWith(): array
    {
        return [
            'conversation_id' => $this->message->parent_id ?? $this->message->id,
            'parent_id' => $this->message->parent_id,
            'message' => [
                'id'        => $this->message->id,
                'senderId'  => $this->message->sender_id,
                'body'      => $this->message->body,
                'time'      => $this->message->created_at->toDateTimeString(),
                'attachments' => $this->message->attachments->map(fn ($a) => [
                    'id'   => $a->id,
                    'name' => $a->original_name,
                    'url'  => asset('storage/'.$a->path),
                    'size' => $a->size,
                    'mime' => $a->mime_type,
                ])->toArray(),
            ],
        ];
    }
}
