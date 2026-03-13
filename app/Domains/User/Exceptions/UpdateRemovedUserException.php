<?php

declare(strict_types=1);

namespace App\Domains\User\Exceptions;

use App\Infrastructure\Exceptions\UpdateRemovedEntityException;

class UpdateRemovedUserException extends UpdateRemovedEntityException
{
    protected string $entityName = 'user';
}
