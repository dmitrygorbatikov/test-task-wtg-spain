<?php

declare(strict_types=1);

namespace App\Infrastructure\Enums;

enum EnvironmentEnum: string
{
    case Local = 'local';
    case Testing = 'testing';
    case Development = 'dev';
    case Stage = 'stage';
    case Production = 'prod';

    public static function isLocal(): bool
    {
        return self::checkWithConfig(self::Local);
    }

    public static function isTesting(): bool
    {
        return self::checkWithConfig(self::Testing);
    }

    public static function isNotForTesting(): bool
    {
        return !self::isTesting();
    }

    public static function isDevelopment(): bool
    {
        return self::checkWithConfig(self::Development);
    }

    public static function isStage(): bool
    {
        return self::checkWithConfig(self::Stage);
    }

    public static function isProduction(): bool
    {
        return self::checkWithConfig(self::Production);
    }

    public static function isNotProduction(): bool
    {
        return !self::isProduction();
    }

    public static function getProtocol(): string
    {
        return self::isLocal() ? 'http://' : 'https://';
    }

    private static function checkWithConfig(self $enum): bool
    {
        return config('app.env') === $enum->value;
    }
}
