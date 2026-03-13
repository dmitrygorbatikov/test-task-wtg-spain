<?php

declare(strict_types=1);

namespace App\Domains\User\Exceptions;

use App\Infrastructure\Exceptions\ConflictException;

class UserEmailAlreadyTakenConflictException extends ConflictException
{
    public function getErrorKey(): string
    {
        return 'email_already_taken';
    }
}
