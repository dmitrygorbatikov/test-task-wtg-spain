<?php

declare(strict_types=1);

namespace App\UI\Api\Http\Resources\Auth;

use App\Domains\PersonalAccessToken\Dto\CodeIdentifierWithMailCodeData;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin CodeIdentifierWithMailCodeData
 */
class CodeIdentifierWithMailCodeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'code' => $this->code,
            'codeIdentifier' => $this->codeIdentifier,
        ];
    }
}
