<?php

namespace App\UI\Broadcasting\Events\Message;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

class MessageReadEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets;

    public int $chatId;

    public function __construct(int $chatId)
    {
        $this->chatId = $chatId;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('chat.' . $this->chatId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'message.read';
    }

    public function broadcastWith(): array
    {
        return [
            'chat_id' => $this->chatId,
        ];
    }
}
