<?php

declare(strict_types=1);

namespace App\Domains\User\Exceptions;

use App\Infrastructure\Exceptions\EntityFindException;

class UserFindException extends EntityFindException
{
    protected string $entityName = 'user';
}
