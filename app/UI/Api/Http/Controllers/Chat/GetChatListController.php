<?php

declare(strict_types=1);

namespace App\UI\Api\Http\Controllers\Chat;

use App\Application\Chat\Actions\GetChatListAction;
use App\UI\Api\Http\Requests\Chat\GetChatListRequest;
use App\UI\Api\Http\Resources\Chat\GetChatListResource;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use App\Infrastructure\Http\Controllers\Controller;

class GetChatListController extends Controller
{
    /**
     * @throws AuthenticationException
     */
    public function __invoke(
        GetChatListRequest $request,
        GetChatListAction $getChatListAction
    ): JsonResponse {
        $chats = $getChatListAction->execute(
            page: $request->getPage(),
            perPage: $request->getPerPage(),
            authUser: $request->getAuthUser()
        );

        return $this->buildPaginatedResponse(
            GetChatListResource::class,
            $chats,
            'chats',
        );
    }
}
