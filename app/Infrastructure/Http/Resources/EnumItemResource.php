<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Infrastructure\Interfaces\Enum\EnumInterface;

/**
 * @property string $value
 *
 * @mixin EnumInterface
 */
class EnumItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->value,
            'title' => $this->getTitle(),
        ];
    }
}
