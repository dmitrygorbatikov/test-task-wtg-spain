<?php

declare(strict_types=1);

namespace App\UI\Api\Http\Resources\User\Me;

use App\Domains\User\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Infrastructure\Http\Resources\EnumItemResource;

/**
 * @mixin User
 */
class UserItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => new EnumItemResource($this->status),
            'firstName' => $this->first_name,
            'lastName' => $this->last_name,
            'email' => $this->email,
            'slug' => $this->slug,
        ];
    }
}
