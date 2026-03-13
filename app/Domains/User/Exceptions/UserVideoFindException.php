<?php

declare(strict_types=1);

namespace App\Domains\User\Exceptions;

use App\Infrastructure\Exceptions\EntityFindException;

class UserVideoFindException extends EntityFindException
{
    protected string $entityName = 'user_video';
}
