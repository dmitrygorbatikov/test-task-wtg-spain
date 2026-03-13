<?php

declare(strict_types=1);

namespace App\Domains\Chat\Exceptions;

use App\Infrastructure\Exceptions\EntityFindException;

class ChatFindException extends EntityFindException
{
    protected string $entityName = 'chat';
}
