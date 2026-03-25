<?php

namespace App\Application\Message\Actions;

use App\Domains\Chat\Eloquent\ChatEloquent;
use App\Domains\Message\Eloquent\MessageEloquent;
use App\Domains\Chat\Exceptions\ChatFindException;
use App\Domains\User\Models\User;
use Illuminate\Support\Facades\Redis;

readonly class ReadMessagesAction
{
    public function __construct(
        private ChatEloquent $chatEloquent,
        private MessageEloquent $messageEloquent,
    ) {
    }

    /**
     * @throws ChatFindException
     */
    public function execute(int $chatId, User $authUser): void
    {
        $chatIsExist = $this->chatEloquent->existsByChat(chatId: $chatId, authUserId: $authUser->getKey());

        if (!$chatIsExist) {
            throw new ChatFindException();
        }

        $this->messageEloquent->readChatMessagesBuilder($chatId);

        Redis::publish('chat.message.read', json_encode(['chatId' => $chatId]));
    }
}
