<?php

declare(strict_types=1);

namespace App\Infrastructure\Models;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use RuntimeException;

/**
 * @property int $id
 * @property string $uuid
 * @property string $connection
 * @property string $queue
 * @property array $payload
 * @property string $exception
 * @property Carbon $failed_at
 */
class FailedJob extends Model
{
    public const CREATED_AT = null;
    public const UPDATED_AT = null;

    protected $table = 'failed_jobs';

    protected $casts = [
        'payload' => 'array',
        'failed_at' => 'datetime',
    ];

    public function getJobName(): string
    {
        return Arr::get($this->payload, 'displayName', 'N/A');
    }

    protected static function booted(): void
    {
        $exception = new RuntimeException(sprintf('The %s model is not intended to be used directly.', self::class));

        static::creating(static function (self $model) use ($exception): void {
            throw $exception;
        });
        static::updating(static function (self $model) use ($exception): void {
            throw $exception;
        });
        static::deleting(static function (self $model) use ($exception): void {
            throw $exception;
        });
    }
}
