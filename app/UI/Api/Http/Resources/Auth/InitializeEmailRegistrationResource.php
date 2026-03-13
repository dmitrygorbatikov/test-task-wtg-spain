<?php

declare(strict_types=1);

namespace App\UI\Api\Http\Resources\Auth;

use App\Domains\User\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin User
 */
class InitializeEmailRegistrationResource extends JsonResource
{
    public function __construct(
        $resource,
        private readonly ?string $codeIdentifier
    ) {
        parent::__construct($resource);
    }

    public function toArray(Request $request): array
    {
        if ($this->codeIdentifier !== null) {
            return [
                'email' => $this->email,
                'codeIdentifier' => $this->codeIdentifier,
            ];
        }

        return [
            'email' => $this->email,
        ];
    }
}
