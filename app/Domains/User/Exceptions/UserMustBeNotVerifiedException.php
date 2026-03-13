<?php

declare(strict_types=1);

namespace App\Domains\User\Exceptions;

use App\Infrastructure\Exceptions\ForbiddenException;

class UserMustBeNotVerifiedException extends ForbiddenException
{
    public function getErrorCode(): string
    {
        return 'user.verification.must_be_not_verified';
    }
}
