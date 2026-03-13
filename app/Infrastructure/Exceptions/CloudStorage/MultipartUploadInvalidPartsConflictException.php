<?php

declare(strict_types=1);

namespace App\Infrastructure\Exceptions\CloudStorage;

use App\Infrastructure\Exceptions\ConflictException;

class MultipartUploadInvalidPartsConflictException extends ConflictException
{
    public function getErrorKey(): string
    {
        return 'multipart_upload_invalid_parts_conflict';
    }
}
