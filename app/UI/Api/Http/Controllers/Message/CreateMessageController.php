<?php

declare(strict_types=1);

namespace App\UI\Api\Http\Controllers\Message;

use App\Application\Message\Actions\CreateMessageAction;
use App\Domains\Chat\Exceptions\ChatFindException;
use App\Infrastructure\Exceptions\EntityCreateException;
use App\UI\Api\Http\Requests\Message\CreateMessageRequest;
use App\UI\Api\Http\Resources\Message\MessageItemResource;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use App\Infrastructure\Http\Controllers\Controller;

class CreateMessageController extends Controller
{
    /**
     * @throws ChatFindException
     * @throws AuthenticationException
     * @throws EntityCreateException
     */
    public function __invoke(
        CreateMessageRequest $request,
        CreateMessageAction $createChatAction,
    ): JsonResponse {
        $message = $createChatAction->execute(
            messageData: $request->getData(),
            authUser: $request->getAuthUser()
        );

        return response()->json(new MessageItemResource($message));
    }
}
