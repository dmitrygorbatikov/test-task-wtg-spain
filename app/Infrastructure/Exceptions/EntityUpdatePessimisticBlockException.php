<?php

declare(strict_types=1);

namespace App\Infrastructure\Exceptions;

class EntityUpdatePessimisticBlockException extends ConflictException
{
    protected array $data = [];

    public static function forResponse(array $data): self
    {
        return (new self())->setData($data);
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function getErrorKey(): string
    {
        return 'entity_pessimistic_block';
    }
}
