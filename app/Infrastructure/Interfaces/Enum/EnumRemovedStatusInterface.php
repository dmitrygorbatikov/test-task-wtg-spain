<?php

declare(strict_types=1);

namespace App\Infrastructure\Interfaces\Enum;

interface EnumRemovedStatusInterface
{
    public function isRemoved(): bool;
    public function isRemovedByUser(): bool;

    /**
     * @return self[]
     */
    public static function getNotRemovedStatuses(): array;
    /**
     * @return self[]
     */
    public static function getRemovedStatuses(): array;
}
