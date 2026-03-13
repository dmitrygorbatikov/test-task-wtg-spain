<?php

namespace App\Domains\Chat\Eloquent;

use App\Domains\Chat\Exceptions\ChatFindException;
use App\Domains\Chat\Models\Chat;
use App\Domains\Message\Models\Message;
use App\Domains\User\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\QueryException;
use RuntimeException;

readonly class ChatEloquent
{
    public function __construct(private Chat $model){

    }

    public function save(Chat $chat): Chat
    {
        $chat->save();

        return $chat;
    }

    public function getAvailableChats(int $page, int $perPage, User $authUser): LengthAwarePaginator
    {

//        Message::getModel()->newQuery()->update(['is_read' => true]);
        return $this->model::query()
            ->with(['lastChatMessage', 'first', 'second'])
            ->withCount([
                'messages as unread_count' => function ($query) use ($authUser) {
                    $query->where('sender_id', '!=', $authUser->getKey())
                        ->where('is_read', false);
                }
            ])
            ->where('first_id', $authUser->getKey())
            ->orWhere('second_id', $authUser->getKey())
            ->orderBy('last_message_at', 'desc')
            ->paginate(perPage: $perPage, page: $page);
    }

    public function existsByUser(int $secondId, int $authUserId): bool
    {
        return $this->model::query()
            ->where(function (Builder $q) use ($secondId, $authUserId) {
                $q->where('second_id', '=', $secondId)
                    ->where('first_id', '=', $authUserId);
            })
            ->orWhere(function (Builder $q) use ($secondId, $authUserId) {
                $q->where('first_id', '=', $secondId)
                    ->where('second_id', '=', $authUserId);
            })
            ->exists();
    }

    public function existsByChat(int $chatId, int $authUserId): bool
    {
        return $this->model::query()
            ->where(function (Builder $q) use ($chatId, $authUserId) {
                $q->where('id', '=', $chatId)
                    ->where('first_id', '=', $authUserId);
            })
            ->orWhere(function (Builder $q) use ($chatId, $authUserId) {
                $q->where('id', '=', $chatId)
                    ->where('second_id', '=', $authUserId);
            })
            ->exists();
    }

    /**
     * @throws ChatFindException
     */
    public function getBy(string $column, mixed $value, array $select = ['*']): Chat
    {
        try {
            return $this->model->newQuery()
                ->where($column, '=', $value)
                ->select($select)
                ->first() ?? throw new ChatFindException($column);
        } catch (QueryException) {
            throw new RuntimeException("Column not found in {$this->model->getTable()} schema. Column - {$column}");
        }
    }



}
