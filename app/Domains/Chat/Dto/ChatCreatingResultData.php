<?php

declare(strict_types=1);

namespace App\Domains\Chat\Dto;

use App\Domains\Chat\Models\Chat;
use App\Domains\Message\Models\Message;
use App\Infrastructure\Dto\Data;

class ChatCreatingResultData extends Data
{
    public function __construct(
        public Chat $chat,
        public Message $message,
    ) {
    }
}
