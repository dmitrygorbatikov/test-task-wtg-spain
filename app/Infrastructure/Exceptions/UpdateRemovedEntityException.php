<?php

declare(strict_types=1);

namespace App\Infrastructure\Exceptions;

use Illuminate\Support\Str;

abstract class UpdateRemovedEntityException extends LogicException
{
    protected string $entityName = 'entity';

    public function getCodeName(): string
    {
        return $this->entityName;
    }

    public function getEntityName(): string
    {
        return Str::ucfirst(Str::lower(Str::headline($this->entityName)));
    }
}
