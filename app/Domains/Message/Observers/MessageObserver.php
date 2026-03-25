<?php

declare(strict_types=1);

namespace App\Domains\Message\Observers;

use App\Domains\Message\Models\Message;
use Illuminate\Support\Facades\Redis;

readonly class MessageObserver
{
    public function created(Message $message): void
    {
        $sendMessageData = [
            'id' => $message->id,
            'recipientId' => $message->chat->getOtherParticipantId($message->sender_id),
            'chatId' => $message->chat_id,
            'senderId' => $message->sender_id,
            'content' => $message->content,
            'isRead' => false,
            'createdAt' => $message->created_at,
            'updatedAt' => $message->updated_at,
        ];

        Redis::publish('chat.message.sent', json_encode($sendMessageData));
    }
}
