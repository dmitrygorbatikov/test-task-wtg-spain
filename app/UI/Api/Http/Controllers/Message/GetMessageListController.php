<?php

declare(strict_types=1);

namespace App\UI\Api\Http\Controllers\Message;

use App\Application\Message\Actions\GetMessageListAction;
use App\Domains\Chat\Exceptions\ChatFindException;
use App\UI\Api\Http\Requests\Message\GetMessageListRequest;
use App\UI\Api\Http\Resources\Message\GetMessageListResource;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use App\Infrastructure\Http\Controllers\Controller;

class GetMessageListController extends Controller
{
    /**
     * @throws ChatFindException
     * @throws AuthenticationException
     */
    public function __invoke(
        GetMessageListRequest $request,
        GetMessageListAction $getMessageListAction
    ): JsonResponse {
        $messages = $getMessageListAction->execute(
            page: $request->getPage(),
            perPage: $request->getPerPage(),
            chatId: $request->getChatId(),
            authUser: $request->getAuthUser()
        );

        return $this->buildPaginatedResponse(
            GetMessageListResource::class,
            $messages,
            'messages',
        );
    }
}
