<?php

declare(strict_types=1);

namespace App\Infrastructure\Traits\Enum;

use Arr;

trait EnumsTrait
{
    /**
     * @param array<self>|self $data
     *
     * @return self[]
     */
    public static function except(array|self $data = []): array
    {
        return array_filter(
            self::cases(),
            static fn ($enum) => is_array($data) ? !in_array($enum, $data, true) : $enum !== $data
        );
    }

    /**
     * @param array<self> $data
     * @param array<self>|self $needleData
     */
    public static function has(array|self $needleData = [], array $data = []): bool
    {
        return in_array($needleData, $data, true);
    }

    /**
     * @param array<self> $data
     * @param array<self>|self $needleData
     */
    public static function doesntHave(array|self $needleData = [], array $data = []): bool
    {
        return !in_array($needleData, $data, true);
    }

    /**
     * @param array<self> $data
     * @param array<self> $needleData
     */
    public static function getMatchesInArrays(array $needleData = [], array $data = []): array
    {
        $needleStringsData = Arr::map($needleData, static fn ($item) => $item->value);
        $stringsData = Arr::map($data, static fn ($item) => $item->value);

        $arrayMatches = array_intersect($needleStringsData, $stringsData);

        return Arr::map($arrayMatches, static fn ($item) => self::from($item));
    }

    public static function toArray(?array $enums = null): array
    {
        return array_column($enums ?? self::cases(), 'value');
    }

    public function getTitle(): string
    {
        return $this->getDefaultTitle();
    }

    public function getDefaultTitle(): string
    {
        return str($this->name)->headline()->lower()->ucfirst()->toString();
    }
}
