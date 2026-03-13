<?php

namespace App\Domains\Message\Eloquent;

use App\Domains\Message\Models\Message;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

readonly class MessageEloquent
{
    public function __construct(private Message $model){
    }

    public function save(Message $message): Message
    {
        $message->save();

        return $message;
    }

    public function getAvailableMessages(int $page, int $perPage, int $chatId): LengthAwarePaginator
    {
        return $this->model->newQuery()
            ->where('chat_id', '=', $chatId)
            ->orderBy('created_at', 'desc')
            ->paginate(perPage: $perPage, page: $page);
    }

    public function readChatMessagesBuilder(int $chatId): int
    {
        return $this->model->newQuery()
            ->where('chat_id', '=', $chatId)
            ->where('is_read', '=', false)
            ->update(['is_read'  => true]);
    }
}
