<?php

namespace App\UI\Api\Http\Controllers\Message;

use App\Application\Message\Actions\ReadMessagesAction;
use App\Domains\Chat\Exceptions\ChatFindException;
use App\Infrastructure\Http\Controllers\Controller;
use App\UI\Api\Http\Requests\Message\ReadMessagesRequest;
use Illuminate\Auth\AuthenticationException;

class ReadMessageController extends Controller
{
    /**
     * @throws ChatFindException
     * @throws AuthenticationException
     */
    public function __invoke(ReadMessagesRequest $request, ReadMessagesAction $readMessagesAction)
    {
        $readMessagesAction->execute(chatId: $request->getChatId(), authUser: $request->getAuthUser());

        return response()->noContent();
    }

}
