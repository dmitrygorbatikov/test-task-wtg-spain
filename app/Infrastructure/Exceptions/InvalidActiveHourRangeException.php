<?php

declare(strict_types=1);

namespace App\Infrastructure\Exceptions;

class InvalidActiveHourRangeException extends LogicException
{
    public function __construct(
        public readonly int $index,
        public readonly string $dayName,
    ) {
        parent::__construct();
    }

    public function getIndex(): int
    {
        return $this->index;
    }

    public function getDayName(): string
    {
        return $this->dayName;
    }
}
