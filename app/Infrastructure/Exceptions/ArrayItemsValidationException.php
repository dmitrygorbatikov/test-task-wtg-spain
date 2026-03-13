<?php

declare(strict_types=1);

namespace App\Infrastructure\Exceptions;

class ArrayItemsValidationException extends ValidationException
{
    public static function byIndexes(
        array $indexes,
        string $key,
        string $rule = 'exists',
        ?string $message = null,
        ?string $keySuffix = null
    ): self {
        $messages = [];

        foreach ($indexes as $index) {
            $item = "{$key}.{$index}";
            $item = $keySuffix ? "{$item}.{$keySuffix}" : $item;

            $messages[$item] = $message ?? __("validation.{$rule}", [
                'attribute' => $item,
            ]);
        }

        return self::withMessages($messages);
    }

    public static function byIndexesWithValue(
        array $indexes,
        string $key,
        string $parameter,
        string $rule = 'exists',
        ?string $message = null
    ): self {
        $messages = [];

        foreach ($indexes as $index => $value) {
            $item = sprintf($key, $index);

            $messages[$item] = $message ?? __("validation.{$rule}", [
                'attribute' => $item,
                $parameter => $value,
            ]);
        }

        return self::withMessages($messages);
    }
}
