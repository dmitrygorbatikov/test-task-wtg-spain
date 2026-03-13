<?php

declare(strict_types=1);

namespace App\Application\Auth\Actions;

use App\Domains\User\Models\User;
use Laravel\Sanctum\PersonalAccessToken;

readonly class LogoutUserAction
{
    public function execute(User $user): bool
    {
        /** @var PersonalAccessToken $currentUserToken */
        $currentUserToken = $user->currentAccessToken();

        return $currentUserToken->delete();
    }
}
