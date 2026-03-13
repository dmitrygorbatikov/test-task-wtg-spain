<?php

declare(strict_types=1);

namespace App\Infrastructure\Dto;

use Spatie\LaravelData\Data as SpatieData;

class Data extends SpatieData
{
    public function toArray(): array
    {
        $array = parent::toArray();

        $arrayKeyMap = $this->getArrayKeyMap();
        $arrayValueMap = $this->getArrayValueMap();

        foreach ($array as $key => $value) {
            $newKey = $arrayKeyMap[$key] ?? $key;
            $newValue = $arrayValueMap[$key] ?? $value;

            $array[$newKey] = $newValue;
        }

        return $array;
    }

    public static function getVars(): array
    {
        $vars = get_class_vars(static::class);

        return array_filter($vars, static fn ($key) => $key[0] !== '_', ARRAY_FILTER_USE_KEY);
    }

    protected function getArrayKeyMap(): array
    {
        return [];
    }

    protected function getArrayValueMap(): array
    {
        return [];
    }
}
