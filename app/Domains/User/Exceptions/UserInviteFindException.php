<?php

declare(strict_types=1);

namespace App\Domains\User\Exceptions;

use App\Infrastructure\Exceptions\EntityFindException;

class UserInviteFindException extends EntityFindException
{
    protected string $entityName = 'user invite';
}
