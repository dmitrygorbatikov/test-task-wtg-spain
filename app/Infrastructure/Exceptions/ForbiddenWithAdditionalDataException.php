<?php

declare(strict_types=1);

namespace App\Infrastructure\Exceptions;

abstract class ForbiddenWithAdditionalDataException extends ForbiddenException
{
    abstract public function getErrorCode(): string;

    abstract public function getData(): array;
}
