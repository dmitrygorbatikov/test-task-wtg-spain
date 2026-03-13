<?php

declare(strict_types=1);

namespace App\Infrastructure\Dto\Casts;

use Arr;
use BackedEnum;
use App\Infrastructure\Interfaces\Enum\EnumInterface;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;

class EnumArrayCast implements Cast
{
    public function cast(
        DataProperty $property,
        mixed $value,
        array $properties,
        CreationContext $context
    ): mixed {
        // Получаем первый атрибут с проверкой
        $attribute = $property->attributes->first();
        if (! $attribute) {
            throw new \RuntimeException('EnumArrayCast: no attribute found on property');
        }

        /** @var string $enumClass */
        $enumClass = Arr::first($attribute->arguments);

        if (! is_string($enumClass)) {
            throw new \RuntimeException('EnumArrayCast: enum class must be a string');
        }

        $values = is_array($value) ? $value : [$value];

        return array_map(fn($item) => $enumClass::from($this->extractEnumValue($item)), $values);
    }

    private function extractEnumValue(mixed $value): string|int
    {
        return data_get($value, 'name') ?? data_get($value, 'id') ?? $value;
    }
}
