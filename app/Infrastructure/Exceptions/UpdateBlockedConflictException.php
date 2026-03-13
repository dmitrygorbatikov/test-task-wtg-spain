<?php

declare(strict_types=1);

namespace App\Infrastructure\Exceptions;

class UpdateBlockedConflictException extends ConflictException
{
    public function __construct(string $field)
    {
        parent::__construct($field, $field);
    }

    public function getErrorKey(): string
    {
        return 'update_blocked_conflict';
    }
}
