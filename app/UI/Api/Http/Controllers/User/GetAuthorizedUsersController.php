<?php

declare(strict_types=1);

namespace App\UI\Api\Http\Controllers\User;

use App\Application\User\Actions\GetAuthorizedUsersAction;
use App\UI\Api\Http\Requests\User\GetAuthorizedUsersRequest;
use App\UI\Api\Http\Resources\User\UserListItemResource;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use App\Infrastructure\Http\Controllers\Controller;

class GetAuthorizedUsersController extends Controller
{
    /**
     * @throws AuthenticationException
     */
    public function __invoke(
        GetAuthorizedUsersRequest $request,
        GetAuthorizedUsersAction $getAuthorizedUsersAction
    ): JsonResponse {
        $users = $getAuthorizedUsersAction->execute(
            page: $request->getPage(),
            perPage: $request->getPerPage(),
            authUser: $request->getAuthUser(),
            filter: $request->getFilter(),
        );

        return $this->buildPaginatedResponse(
            UserListItemResource::class,
            $users,
            'users',
        );
    }
}
