<?php

declare(strict_types=1);

namespace App\Infrastructure\Exceptions;

abstract class ExternalServiceException extends ServerException
{
    abstract public function getErrorKey(): string;
}
