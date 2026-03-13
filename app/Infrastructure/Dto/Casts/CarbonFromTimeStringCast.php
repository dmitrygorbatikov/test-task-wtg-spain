<?php

declare(strict_types=1);

namespace App\Infrastructure\Dto\Casts;

use Illuminate\Support\Carbon;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;

class CarbonFromTimeStringCast implements Cast
{
    public function cast(
        DataProperty $property,
        mixed $value,
        array $properties,
        CreationContext $context
    ): mixed {
        if ($value === null) {
            return null;
        }

        return Carbon::createFromFormat('H:i', $value)->startOfMinute();
    }
}
