<?php

declare(strict_types=1);

namespace App\Domains\User\Exceptions;

use Illuminate\Support\Str;
use App\Infrastructure\Exceptions\LogicException;

class NotActiveUserException extends LogicException
{
    protected string $entityName = 'user';

    public function getCodeName(): string
    {
        return $this->entityName;
    }

    public function getEntityName(): string
    {
        return Str::ucfirst(Str::lower(Str::headline($this->entityName)));
    }
}
