<?php

declare(strict_types=1);

namespace App\Infrastructure\Exceptions;

abstract class BaseInvalidParameterException extends LogicException
{
    public function __construct(
        private readonly int $index = 0
    ) {
        parent::__construct();
    }

    public function getIndex(): int
    {
        return $this->index;
    }
}
