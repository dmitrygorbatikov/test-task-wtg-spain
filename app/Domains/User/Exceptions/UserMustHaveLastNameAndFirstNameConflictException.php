<?php

declare(strict_types=1);

namespace App\Domains\User\Exceptions;

use App\Infrastructure\Exceptions\ConflictException;

class UserMustHaveLastNameAndFirstNameConflictException extends ConflictException
{
    public function getErrorKey(): string
    {
        return 'user.must_have_last_name_and_first_name_conflict';
    }
}
