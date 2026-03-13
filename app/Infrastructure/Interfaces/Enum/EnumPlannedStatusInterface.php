<?php

declare(strict_types=1);

namespace App\Infrastructure\Interfaces\Enum;

interface EnumPlannedStatusInterface
{
    public function isPlanned(): bool;
}
