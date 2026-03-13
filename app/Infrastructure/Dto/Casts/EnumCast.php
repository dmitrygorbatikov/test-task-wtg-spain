<?php

declare(strict_types=1);

namespace App\Infrastructure\Dto\Casts;

use Arr;
use BackedEnum;
use App\Infrastructure\Interfaces\Enum\EnumInterface;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;

class EnumCast implements Cast
{
    public function cast(
        DataProperty    $property,
        mixed           $value,
        array           $properties,
        CreationContext $context
    ): EnumInterface|BackedEnum {
        /** @var BackedEnum&EnumInterface $enumClass */
        $enumClass = Arr::first($property->attributes->first()->arguments);
        $enumValue = data_get($value, 'name') ?? data_get($value, 'id') ?? $value;

        return $enumClass::from($enumValue);
    }
}
