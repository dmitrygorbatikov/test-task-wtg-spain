<?php

namespace App\Application\Message\Actions;

use App\Domains\Chat\Eloquent\ChatEloquent;
use App\Domains\Chat\Exceptions\ChatFindException;
use App\Domains\Message\Eloquent\MessageEloquent;
use App\Domains\User\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

readonly class GetMessageListAction
{
    public function __construct(
        private MessageEloquent $messageEloquent,
        private ChatEloquent $chatEloquent,
    ) {
    }

    /**
     * @throws ChatFindException
     */
    public function execute(int $page, int $perPage, int $chatId, User $authUser): LengthAwarePaginator
    {
        $chatIsExist = $this->chatEloquent->existsByChat(chatId: $chatId, authUserId: $authUser->getKey());

        if (!$chatIsExist) {
            throw new ChatFindException();
        }

        $messages = $this->messageEloquent->getAvailableMessages(page: $page, perPage: $perPage, chatId: $chatId);

        $messages->getCollection()->transform(function ($item) {
            return $item;
        });

        $messages->setCollection($messages->getCollection()->reverse());

        return $messages;
    }
}
