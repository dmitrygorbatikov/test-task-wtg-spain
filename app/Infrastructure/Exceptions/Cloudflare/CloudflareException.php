<?php

declare(strict_types=1);

namespace App\Infrastructure\Exceptions\Cloudflare;

use App\Infrastructure\Exceptions\ExternalServiceException;

class CloudflareException extends ExternalServiceException
{
    public function getErrorKey(): string
    {
        return 'cloudflare';
    }
}
