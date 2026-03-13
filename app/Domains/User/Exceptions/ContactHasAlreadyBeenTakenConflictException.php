<?php

declare(strict_types=1);

namespace App\Domains\User\Exceptions;

use App\Infrastructure\Exceptions\ConflictException;

class ContactHasAlreadyBeenTakenConflictException extends ConflictException
{
    public function getErrorKey(): string
    {
        return 'user.contact.has_already_been_taken_conflict';
    }
}
