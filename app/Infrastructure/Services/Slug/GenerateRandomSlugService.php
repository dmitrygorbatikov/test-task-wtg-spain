<?php

namespace App\Infrastructure\Services\Slug;

use App\Domains\User\Eloquent\UserEloquent;
use App\Domains\User\Models\User;
use Random\RandomException;

readonly class GenerateRandomSlugService
{
    public function __construct(
        private UserEloquent     $userEloquent,
    ) {
    }

    /**
     * @throws RandomException
     */
    public function execute($model): string
    {
        do {
            $slug = $this->generateRandomSlug(length: 9, prefix: 'id');
        } while ($this->getCondition($slug, $model));

        return $slug;
    }


    /**
     * @throws RandomException
     */
    private function generateRandomSlug(int $length, string $prefix = ''): string
    {
        $slug = $prefix;

        for ($i = 0; $i < $length; ++$i) {
            $slug .= random_int(0, 9);
        }

        return $slug;
    }

    private function getCondition(string $slug, $model): bool
    {
        return match ($model) {
            $model instanceof User => $this->userEloquent->existsBy('slug', $slug),
            default => false
        };

    }

}
