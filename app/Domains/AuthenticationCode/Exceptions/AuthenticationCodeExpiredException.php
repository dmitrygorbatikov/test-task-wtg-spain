<?php

declare(strict_types=1);

namespace App\Domains\AuthenticationCode\Exceptions;

use App\Infrastructure\Exceptions\ForbiddenException;

class AuthenticationCodeExpiredException extends ForbiddenException
{
    public function getErrorCode(): string
    {
        return 'authentication_code.expired';
    }
}
