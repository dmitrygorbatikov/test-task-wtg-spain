<?php

declare(strict_types=1);

namespace App\UI\Api\Http\Resources\Chat;

use App\Domains\Chat\Dto\ChatCreatingResultData;
use App\UI\Api\Http\Resources\Message\MessageItemResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin ChatCreatingResultData
 */
class ChatCreatingResultResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'chat' => new ChatItemResource($this->resource->chat),
            'message' => new MessageItemResource($this->resource->message),
        ];
    }
}
