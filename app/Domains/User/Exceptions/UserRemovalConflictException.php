<?php

declare(strict_types=1);

namespace App\Domains\User\Exceptions;

use App\Infrastructure\Exceptions\ConflictException;

class UserRemovalConflictException extends ConflictException
{
    public function getErrorKey(): string
    {
        return 'user.removal_conflict';
    }
}
