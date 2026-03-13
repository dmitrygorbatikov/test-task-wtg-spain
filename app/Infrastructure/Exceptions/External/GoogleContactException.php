<?php

declare(strict_types=1);

namespace App\Infrastructure\Exceptions\External;

use App\Infrastructure\Exceptions\ExternalServiceException;

class GoogleContactException extends ExternalServiceException
{
    public function getErrorKey(): string
    {
        return 'google';
    }
}
