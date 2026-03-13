<?php

namespace App\UI\Broadcasting\Events\Message;

use App\Domains\Message\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

class NewChatMessageEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets;

    public Message $message;

    public function __construct(Message $message)
    {
        $this->message = $message->load(['sender']);
    }

    public function broadcastOn(): array
    {
        $channels = [
            new Channel("chat.{$this->message->chat_id}"),
        ];

        $recipientId = $this->message->chat->getOtherParticipantId($this->message->sender_id);
        $channels[] = new PrivateChannel("user.{$recipientId}");

        return $channels;
    }

    public function broadcastAs(): string
    {
        return 'new.message';
    }

    public function broadcastWith(): array
    {
        return [
            'message'  => $this->message->toArray(),
            'chat_id'  => $this->message->chat_id,
            'sender'   => $this->message->sender->only(['id', 'firstName', 'lastName', 'avatar']),
        ];
    }
}
