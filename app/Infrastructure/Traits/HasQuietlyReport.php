<?php

declare(strict_types=1);

namespace App\Infrastructure\Traits;

use App\Infrastructure\Exceptions\ReportQuietlyWithApplicationStacktraceException;

trait HasQuietlyReport
{
    protected function quietlyReport(string $message): void
    {
        report(new ReportQuietlyWithApplicationStacktraceException($message));
    }
}
