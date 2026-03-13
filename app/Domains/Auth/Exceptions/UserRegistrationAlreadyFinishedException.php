<?php

declare(strict_types=1);

namespace App\Domains\Auth\Exceptions;

use App\Infrastructure\Exceptions\ForbiddenException;

class UserRegistrationAlreadyFinishedException extends ForbiddenException
{
    public function getErrorCode(): string
    {
        return 'user.registration.already_finished';
    }
}
