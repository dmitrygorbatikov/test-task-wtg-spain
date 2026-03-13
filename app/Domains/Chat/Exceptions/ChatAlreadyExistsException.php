<?php

declare(strict_types=1);

namespace App\Domains\Chat\Exceptions;

use App\Infrastructure\Exceptions\ConflictException;

class ChatAlreadyExistsException extends ConflictException
{
    public function getErrorKey(): string
    {
        return 'chat.has_already_been_taken_conflict';
    }
}
