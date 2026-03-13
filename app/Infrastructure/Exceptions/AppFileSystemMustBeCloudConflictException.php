<?php

declare(strict_types=1);

namespace App\Infrastructure\Exceptions;

class AppFileSystemMustBeCloudConflictException extends ConflictException
{
    public function getErrorKey(): string
    {
        return 'app_file_system_must_be_cloud_conflict';
    }
}
