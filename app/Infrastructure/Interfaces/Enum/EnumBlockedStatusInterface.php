<?php

declare(strict_types=1);

namespace App\Infrastructure\Interfaces\Enum;

interface EnumBlockedStatusInterface
{
    public function isBlocked(): bool;
}
