<?php

declare(strict_types=1);

namespace App\Domains\User\Exceptions;

use App\Infrastructure\Exceptions\EntityRestoreException;

class UserRestoreException extends EntityRestoreException
{
    protected string $entityName = 'user';
}
