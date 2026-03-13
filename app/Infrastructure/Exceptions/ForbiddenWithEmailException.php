<?php

declare(strict_types=1);

namespace App\Infrastructure\Exceptions;

abstract class ForbiddenWithEmailException extends ForbiddenException
{
    abstract public function getErrorCode(): string;

    abstract public function getEmail(): string;
}
