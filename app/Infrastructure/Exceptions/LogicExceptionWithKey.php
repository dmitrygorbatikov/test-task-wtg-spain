<?php

declare(strict_types=1);

namespace App\Infrastructure\Exceptions;

/**
 * In future refactoring try merges this class with LogicException and resolves the conflict
 */
abstract class LogicExceptionWithKey extends ServerException
{
    protected int $key = 0;

    public function getKey(): int
    {
        return $this->key;
    }

    public function setKey(int $key): void
    {
        $this->key = $key;
    }
}
