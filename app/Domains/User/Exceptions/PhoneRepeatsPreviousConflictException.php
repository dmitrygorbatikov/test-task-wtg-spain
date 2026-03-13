<?php

declare(strict_types=1);

namespace App\Domains\User\Exceptions;

use App\Infrastructure\Exceptions\ConflictException;

class PhoneRepeatsPreviousConflictException extends ConflictException
{
    public function getErrorKey(): string
    {
        return 'user.phone.repeats_previous';
    }
}
