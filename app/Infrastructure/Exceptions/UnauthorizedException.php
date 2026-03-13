<?php

declare(strict_types=1);

namespace App\Infrastructure\Exceptions;

abstract class UnauthorizedException extends ServerException
{
    abstract public function getErrorCode(): string;
}
