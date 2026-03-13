<?php

declare(strict_types=1);

namespace App\Infrastructure\Interfaces\Enum;

interface EnumNotActiveStatusInterface
{
    public function isNotActive(): bool;
}
