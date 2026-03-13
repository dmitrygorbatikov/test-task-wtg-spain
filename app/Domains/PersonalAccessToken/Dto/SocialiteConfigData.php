<?php

declare(strict_types=1);

namespace App\Domains\PersonalAccessToken\Dto;

use App\Infrastructure\Dto\{Data};

class SocialiteConfigData extends Data
{
    public function __construct(
        public readonly ?string $clientId,
        public readonly ?string $clientSecret,
        public readonly ?string $redirectUrl,
        public readonly ?array $additionalConfig,
    ) {
    }
}
