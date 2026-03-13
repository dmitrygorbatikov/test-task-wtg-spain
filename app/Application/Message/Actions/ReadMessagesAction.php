<?php

namespace App\Application\Message\Actions;

use App\UI\Broadcasting\Events\Message\MessageReadEvent;
use App\Domains\Chat\Eloquent\ChatEloquent;
use App\Domains\Message\Eloquent\MessageEloquent;
use App\Domains\Chat\Exceptions\ChatFindException;
use App\Domains\User\Models\User;

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

        broadcast(new MessageReadEvent($chatId))->toOthers();
    }
}
