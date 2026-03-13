<?php

declare(strict_types=1);

namespace App\Infrastructure\Exceptions;

class PipedriveException extends ExternalServiceException
{
    public function getErrorKey(): string
    {
        return 'pipedrive';
    }
}
