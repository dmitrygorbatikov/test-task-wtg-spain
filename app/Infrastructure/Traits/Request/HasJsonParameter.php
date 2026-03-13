<?php

declare(strict_types=1);

namespace App\Infrastructure\Traits\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * @mixin FormRequest
 */
trait HasJsonParameter
{
    protected function tryParseJsonData(mixed $data): ?array
    {
        try {
            if (is_string($data) && json_validate($data)) {
                $data = json_decode($data, true, 512, JSON_THROW_ON_ERROR);
            }

            if ($data === null) {
                return null;
            }

            if (is_array($data)) {
                return array_map(static fn ($value) => $value === '' ? null : $value, $data);
            }
        } catch (Throwable $e) {
            Log::error("Failed parse json: {$e->getMessage()}", [
                'data' => $data,
            ]);
        }

        return null;
    }
}
