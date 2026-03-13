<?php

declare(strict_types=1);

namespace App\Domains\Auth\Exceptions;

use App\Infrastructure\Exceptions\UnauthorizedException;

class InvalidLoginCredentialsException extends UnauthorizedException
{
    public function getErrorCode(): string
    {
        return 'login.invalid_credentials';
    }
}
