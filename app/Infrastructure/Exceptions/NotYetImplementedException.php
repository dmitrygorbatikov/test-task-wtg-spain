<?php

declare(strict_types=1);

namespace App\Infrastructure\Exceptions;

class NotYetImplementedException extends LogicException
{
    public function __construct(string $message = 'This feature is not yet implemented.')
    {
        parent::__construct($message);
    }
}
