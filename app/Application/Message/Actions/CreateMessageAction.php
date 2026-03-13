<?php

namespace App\Application\Message\Actions;

use App\Domains\Chat\Eloquent\ChatEloquent;
use App\Domains\Message\Dto\MessageData;
use App\Domains\Message\Eloquent\MessageEloquent;
use App\Domains\Message\Models\Message;
use App\Domains\Chat\Exceptions\ChatFindException;
use App\Domains\User\Models\User;
use App\Infrastructure\Exceptions\EntityCreateException;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Throwable;

readonly class CreateMessageAction
{
    public function __construct(
        private ChatEloquent $chatEloquent,
        private MessageEloquent $messageEloquent,
    ) {
    }

    /**
     * @throws ChatFindException
     * @throws EntityCreateException
     */
    public function execute(MessageData $messageData, User $authUser): Message
    {
        $chatIsExist = $this->chatEloquent->existsByChat(chatId: $messageData->chatId, authUserId: $authUser->getKey());

        if (!$chatIsExist) {
            throw new ChatFindException();
        }

        $chat = $this->chatEloquent->getBy('id', $messageData->chatId);

        try {
            return DB::transaction(function () use ($messageData, $authUser, $chat): Message {
                $message = new Message();

                $message->chat_id = $messageData->chatId;
                $message->sender_id = $authUser->getKey();
                $message->content = $messageData->content;

                $chat->last_message_at = Carbon::now();

                $this->chatEloquent->save($chat);

                return $this->messageEloquent->save($message);
            });
        } catch (Throwable $e) {
            throw new EntityCreateException(message: 'Error while creating message', previous: $e);
        }



    }
}
