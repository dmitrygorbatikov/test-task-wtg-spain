<?php

declare(strict_types=1);

namespace App\Infrastructure\Interfaces\Model;

interface CascadeSoftDeletable
{
    public function validateCascadingSoftDelete(): void;
    public function runCascadingDeletes(): void;
    public function runCascadingDetach(): void;
    public function getCascadingDeletes(): array;
    public function getCascadingDetach(): array;
}
