<?php

declare(strict_types=1);

namespace App\Infrastructure\Exceptions;

class AwsActionConflictException extends ConflictException
{
    public function getErrorKey(): string
    {
        return 'aws_action_conflict';
    }
}
