<?php

declare(strict_types=1);

namespace App\Domains\PersonalAccessToken\Dto;

use App\Infrastructure\Dto\{Data};

class SocialUserData extends Data
{
    public function __construct(
        public readonly ?string $id,
        public readonly ?string $firstName,
        public readonly ?string $lastName,
        public readonly ?string $email,
        public readonly ?string $dateOfBirth,
    ) {
    }
}
