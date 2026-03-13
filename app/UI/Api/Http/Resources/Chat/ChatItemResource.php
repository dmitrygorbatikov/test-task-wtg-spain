<?php

declare(strict_types=1);

namespace App\UI\Api\Http\Resources\Chat;

use App\Domains\Chat\Models\Chat;
use App\UI\Api\Http\Resources\User\Me\UserItemResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Chat
 */
class ChatItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {

        return [
            'id' => $this->resource->id,
            'first' => new UserItemResource($this->resource->first),
            'second' => new UserItemResource($this->resource->second),
            'lastMessageAt' => $this->resource->last_message_at,
            'createdAt' => $this->resource->created_at,
        ];
    }
}
