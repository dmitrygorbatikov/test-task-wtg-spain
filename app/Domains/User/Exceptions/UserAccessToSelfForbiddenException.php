<?php

declare(strict_types=1);

namespace App\Domains\User\Exceptions;

use App\Infrastructure\Exceptions\ForbiddenException;

class UserAccessToSelfForbiddenException extends ForbiddenException
{
    public function getErrorCode(): string
    {
        return 'user_access_to_self_forbidden';
    }
}
