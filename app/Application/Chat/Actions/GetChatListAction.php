<?php

namespace App\Application\Chat\Actions;

use App\Domains\Chat\Eloquent\ChatEloquent;
use App\Domains\Message\Models\Message;
use App\Domains\User\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

readonly class GetChatListAction
{
    public function __construct(private ChatEloquent $chatEloquent) {
    }

    public function execute(int $page, int $perPage, User $authUser): LengthAwarePaginator
    {
        return $this->chatEloquent->getAvailableChats(page: $page, perPage: $perPage, authUser: $authUser);
    }
}
