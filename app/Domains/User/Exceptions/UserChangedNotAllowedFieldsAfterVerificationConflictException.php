<?php

declare(strict_types=1);

namespace App\Domains\User\Exceptions;

use App\Infrastructure\Exceptions\ConflictException;

class UserChangedNotAllowedFieldsAfterVerificationConflictException extends ConflictException
{
    public function getErrorKey(): string
    {
        return 'user.changed_not_allowed_fields_after_verification';
    }
}
