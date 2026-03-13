<?php

declare(strict_types=1);

namespace App\Domains\AuthenticationCode\Exceptions;

use App\Infrastructure\Exceptions\EntityFindException;

class AuthenticationCodeFindException extends EntityFindException
{
    protected string $entityName = 'authenticationCode';
}
