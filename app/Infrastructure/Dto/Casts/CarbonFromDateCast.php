<?php

declare(strict_types=1);

namespace App\Infrastructure\Dto\Casts;

use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;

class CarbonFromDateCast implements Cast
{
    /**
     * @throws ValidationException
     */
    public function cast(
        DataProperty $property,
        mixed $value,
        array $properties,
        CreationContext $context
    ): mixed {
        try {
            return Carbon::parse($value);
        } catch (\Exception $e) {
            throw ValidationException::withMessages([
                $property->name => "Invalid date format: {$value}",
            ]);
        }
    }
}
