<?php

declare(strict_types=1);

namespace App\Domains\Chat\Dto;

use App\Infrastructure\Dto\Data;

class ChatData extends Data
{
    public function __construct(
        public int $secondId,
        public string $message,
    ) {
    }
}
