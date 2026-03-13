<?php

declare(strict_types=1);

namespace App\Domains\User\Exceptions;

use App\Infrastructure\Exceptions\ConflictException;

class EmailRepeatsPreviousConflictException extends ConflictException
{
    public function getErrorKey(): string
    {
        return 'user.email.repeats_previous';
    }
}
