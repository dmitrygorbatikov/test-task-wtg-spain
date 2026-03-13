<?php

namespace App\Application\Chat\Actions;

use App\Domains\Chat\Eloquent\ChatEloquent;
use App\Domains\Chat\Exceptions\ChatFindException;
use App\Domains\Chat\Models\Chat;
use App\Domains\User\Models\User;

readonly class GetChatItemAction
{
    public function __construct(private ChatEloquent $chatEloquent) {
    }

    /**
     * @throws ChatFindException
     */
    public function execute(int $chatId, User $authUser): Chat
    {
        $chatIsExist = $this->chatEloquent->existsByChat(chatId: $chatId, authUserId: $authUser->getKey());

        if (!$chatIsExist) {
            throw new ChatFindException();
        }

        return $this->chatEloquent->getBy('id', $chatId);
    }
}
