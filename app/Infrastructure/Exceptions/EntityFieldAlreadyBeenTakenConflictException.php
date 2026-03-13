<?php

declare(strict_types=1);

namespace App\Infrastructure\Exceptions;

class EntityFieldAlreadyBeenTakenConflictException extends ConflictException
{
    public function __construct(string $field, string $entityName)
    {
        parent::__construct($field, $entityName);
    }

    public function getErrorKey(): string
    {
        return 'has_already_been_taken_conflict';
    }
}
