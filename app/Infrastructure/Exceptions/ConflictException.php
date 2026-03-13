<?php

declare(strict_types=1);

namespace App\Infrastructure\Exceptions;

abstract class ConflictException extends LogicException
{
    private string $errorMessage = '';
    private array $data = [];

    public function __construct(?string $field = null, ?string $entityName = null, ?string $errorMessage = null)
    {
        parent::__construct();

        if ($field) {
            $this->setEntityField($field);
        }

        if ($entityName) {
            $this->setEntityName($entityName);
        }

        if ($errorMessage) {
            $this->errorMessage = $errorMessage;
        }
    }

    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }

    public function setData(array $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function getData(): array
    {
        return $this->data;
    }

    abstract public function getErrorKey(): string;
}
