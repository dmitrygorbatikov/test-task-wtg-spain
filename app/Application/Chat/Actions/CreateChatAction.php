<?php

namespace App\Application\Chat\Actions;

use App\Domains\Chat\Dto\ChatCreatingResultData;
use App\Domains\Chat\Dto\ChatData;
use App\Domains\Chat\Eloquent\ChatEloquent;
use App\Domains\Chat\Exceptions\ChatAlreadyExistsException;
use App\Domains\Chat\Models\Chat;
use App\Domains\Message\Eloquent\MessageEloquent;
use App\Domains\Message\Models\Message;
use App\Domains\User\Models\User;
use App\Infrastructure\Exceptions\EntityCreateException;
use Illuminate\Support\Facades\DB;
use Throwable;

readonly class CreateChatAction
{
    public function __construct(
        private ChatEloquent $chatEloquent,
        private MessageEloquent $messageEloquent,
    ) {
    }

    /**
     * @throws ChatAlreadyExistsException
     * @throws EntityCreateException
     */
    public function execute(ChatData $data, User $authUser): ChatCreatingResultData
    {
        $chatIsExist = $this->chatEloquent->existsByUser(secondId: $data->secondId, authUserId: $authUser->getKey());

        if ($chatIsExist) {
            throw new ChatAlreadyExistsException();
        }

        try {
            return DB::transaction(function () use ($data, $authUser) {
                $chat = new Chat();

                $chat->first_id = $authUser->getKey();
                $chat->second_id = $data->secondId;
                $chat->last_message_at = now();

                $createdChat = $this->chatEloquent->save($chat);

                $message = new Message();

                $message->chat_id = $createdChat->getKey();
                $message->sender_id = $authUser->getKey();
                $message->content = $data->message;

                return new ChatCreatingResultData(
                    chat: $createdChat,
                    message: $this->messageEloquent->save($message),
                );
            }, config('database.transaction_attempt_count'));
        } catch (Throwable $e) {
            throw new EntityCreateException('Failed to create chat with message: ' . $e->getMessage());
        }
    }
}
