<?php

declare(strict_types=1);

namespace App\UI\Api\Http\Controllers\Chat;

use App\Application\Chat\Actions\GetChatItemAction;
use App\Domains\Chat\Exceptions\ChatFindException;
use App\UI\Api\Http\Requests\Chat\GetChatItemRequest;
use App\UI\Api\Http\Resources\Chat\ChatItemResource;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use App\Infrastructure\Http\Controllers\Controller;

class GetChatItemController extends Controller
{
    /**
     * @throws AuthenticationException
     * @throws ChatFindException
     */
    public function __invoke(
        GetChatItemRequest $request,
        GetChatItemAction $getChatItemAction
    ): JsonResponse {
        $chat = $getChatItemAction->execute(
            chatId: $request->getChatId(),
            authUser: $request->getAuthUser()
        );

        return response()->json(new ChatItemResource($chat));
    }
}
