<?php

declare(strict_types=1);

namespace App\Infrastructure\Interfaces\Enum;

use BackedEnum;

interface EnumInterface extends BackedEnum
{
    public function getTitle(): string;
}
