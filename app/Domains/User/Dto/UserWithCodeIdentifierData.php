<?php

declare(strict_types=1);

namespace App\Domains\User\Dto;

use App\Domains\User\Models\User;
use App\Infrastructure\Dto\Data;

class UserWithCodeIdentifierData extends Data
{
    public function __construct(
        public readonly User $user,
        public readonly ?string $codeIdentifier,
    ) {
    }
}
