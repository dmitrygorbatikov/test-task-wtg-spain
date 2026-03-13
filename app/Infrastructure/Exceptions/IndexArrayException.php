<?php

declare(strict_types=1);

namespace App\Infrastructure\Exceptions;

class IndexArrayException extends LogicException
{
    public function __construct(
        private readonly array $indexes = []
    ) {
        parent::__construct();
    }

    public function getIndexes(): array
    {
        return $this->indexes;
    }
}
