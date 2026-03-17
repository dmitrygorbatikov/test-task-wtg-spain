<?php

declare(strict_types=1);

namespace App\UI\Api\Http\Resources\Message;

use App\Domains\Chat\Models\Chat;
use App\Domains\Message\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Message
 */
class GetMessageListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'chatId' => $this->chat_id,
            'senderId' => $this->sender_id,
            'content' => $this->content,
            'isRead' => $this->is_read,
            'createdAt' => $this->created_at,
        ];
    }
}
