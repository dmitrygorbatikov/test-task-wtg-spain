<?php

declare(strict_types=1);

namespace App\UI\Api\Http\Controllers\Chat;

use App\Application\Chat\Actions\CreateChatAction;
use App\Domains\Chat\Exceptions\ChatAlreadyExistsException;
use App\UI\Api\Http\Requests\Chat\CreateChatRequest;
use App\UI\Api\Http\Resources\Chat\ChatCreatingResultResource;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use App\Infrastructure\Exceptions\EntityCreateException;
use App\Infrastructure\Http\Controllers\Controller;

class CreateChatController extends Controller
{
    /**
     * @throws AuthenticationException
     * @throws ChatAlreadyExistsException
     * @throws EntityCreateException
     */
    public function __invoke(
        CreateChatRequest $request,
        CreateChatAction $createChatAction,
    ): JsonResponse {
        $chat = $createChatAction->execute(
            data: $request->getData(),
            authUser: $request->getAuthUser()
        );

        return response()->json(new ChatCreatingResultResource($chat));
    }
}
