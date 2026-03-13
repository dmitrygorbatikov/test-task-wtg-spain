<?php

declare(strict_types=1);

namespace App\Infrastructure\Exceptions;

class SlugNotAllowedConflictException extends ConflictException
{
    public function getErrorKey(): string
    {
        return 'slug_not_allowed';
    }
}
