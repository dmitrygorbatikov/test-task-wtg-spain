<?php

declare(strict_types=1);

namespace App\Domains\User\Exceptions;

use App\Infrastructure\Exceptions\ConflictException;

class UserRestoreConflictException extends ConflictException
{
    public function getErrorKey(): string
    {
        return 'user.restore_conflict';
    }
}
