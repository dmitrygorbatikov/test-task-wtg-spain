<?php

namespace App\Application\User\Actions;

use App\Domains\User\Dto\FilterParametersData;
use App\Domains\User\Eloquent\UserEloquent;
use App\Domains\User\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

readonly class GetAuthorizedUsersAction
{
    public function __construct(private UserEloquent $userEloquent) {
    }

    public function execute(int $page, int $perPage, User $authUser, ?FilterParametersData $filter): LengthAwarePaginator
    {
        return $this->userEloquent->getAvailableUsers(
            page: $page,
            perPage: $perPage,
            authUser: $authUser,
            filter: $filter
        );
    }
}
