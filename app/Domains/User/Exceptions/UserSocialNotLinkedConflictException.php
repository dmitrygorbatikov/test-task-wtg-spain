<?php

declare(strict_types=1);

namespace App\Domains\User\Exceptions;

use App\Infrastructure\Exceptions\ConflictException;

class UserSocialNotLinkedConflictException extends ConflictException
{
    public function getErrorKey(): string
    {
        return 'user.social.not_linked_conflict';
    }
}
