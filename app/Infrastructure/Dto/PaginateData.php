<?php

declare(strict_types=1);

namespace App\Infrastructure\Dto;

class PaginateData
{
    public function __construct(
        public int $page,
        public int $perPage,
    ) {
    }
}
