<?php

declare(strict_types=1);

namespace App\Domains\User\Exceptions;

use App\Infrastructure\Exceptions\ForbiddenException;

class UserNotVerifiedException extends ForbiddenException
{
    public function getErrorCode(): string
    {
        return 'user.verification.not_verified';
    }
}
