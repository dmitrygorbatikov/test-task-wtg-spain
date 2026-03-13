<?php

declare(strict_types=1);

namespace App\Infrastructure\Exceptions;

use RuntimeException;
use Throwable;

class ReportQuietlyWithApplicationStacktraceException extends RuntimeException
{
    public function __construct(string $message, int $code = 0, ?Throwable $previous = null)
    {
        $getOnlyAppStackTrace = $this->getOnlyAppStackTrace($this->getTraceAsString());
        parent::__construct("{$message} -- [stacktrace]: {$getOnlyAppStackTrace}", $code, $previous);
    }

    private function getOnlyAppStackTrace(string $trace): string
    {
        $lines = explode("\n", $trace);
        $appLines = array_filter($lines, static fn (string $line) => !str_contains($line, '/var/www/vendor'));

        return implode('; -- ', $appLines);
    }
}
