<?php

declare(strict_types=1);

namespace App\Infrastructure\Interfaces\Enum;

interface EnumDraftStatusInterface
{
    public function isDraft(): bool;
}
