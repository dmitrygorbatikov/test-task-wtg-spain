<?php

declare(strict_types=1);

namespace App\Infrastructure\Exceptions;

class InvalidCredentialsException extends UnauthorizedException
{
    public function getErrorCode(): string
    {
        return 'invalid_credentials';
    }
}
