<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CorrespondenceRefresh implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    public int $conversationId;

    public function __construct(int $conversationId)
    {
        $this->conversationId = $conversationId;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('correspondence.refresh');
    }

    public function broadcastAs(): string
    {
        return 'correspondence.refresh';
    }

    public function broadcastWith(): array
    {
        return [
            'conversation_id' => $this->conversationId,
        ];
    }
}
