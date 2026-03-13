<?php

declare(strict_types=1);

namespace App\Infrastructure\Exceptions;

class HeaderNotFoundException extends ConflictException
{
    public function setHeader(string $header): self
    {
        $this->setData([
            'header' => $header,
        ]);

        return $this;
    }

    public function getErrorKey(): string
    {
        return 'header_not_found';
    }
}
