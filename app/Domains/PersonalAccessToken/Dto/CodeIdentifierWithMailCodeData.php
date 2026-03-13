<?php

declare(strict_types=1);

namespace App\Domains\PersonalAccessToken\Dto;

use App\Infrastructure\Dto\Data;

class CodeIdentifierWithMailCodeData extends Data
{
    public function __construct(
        public readonly string $code,
        public readonly string $codeIdentifier,
    ) {
    }
}
