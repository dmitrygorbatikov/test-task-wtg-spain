<?php

declare(strict_types=1);

namespace App\Domains\Message\Dto;

use App\Infrastructure\Dto\Data;

class MessageData extends Data
{
    public function __construct(
        public int $chatId,
        public string $content,
    ) {
    }
}
