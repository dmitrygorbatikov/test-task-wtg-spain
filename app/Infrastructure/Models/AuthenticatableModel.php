<?php

declare(strict_types=1);

namespace App\Infrastructure\Models;

use App\Domains\PersonalAccessToken\Models\PersonalAccessToken;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Auth\{Authenticatable, MustVerifyEmail};
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\{Authenticatable as AuthenticatableContract,
    CanResetPassword as CanResetPasswordContract
};
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Foundation\Auth\User;
use Laravel\Sanctum\Contracts\HasApiTokens as HasApiTokensInterface;
use Laravel\Sanctum\HasApiTokens;

/**
 * @see User
 */
class AuthenticatableModel extends Model implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract,
    HasApiTokensInterface
{
    use Authenticatable;
    use Authorizable;
    use CanResetPassword;
    use HasApiTokens;
    use MustVerifyEmail;

    public function getCurrentPersonalAccessToken(): ?PersonalAccessToken
    {
        /** @var ?PersonalAccessToken $accessToken */
        $accessToken = $this->currentAccessToken();

        return $accessToken;
    }

    public function clearTokens(): bool
    {
        return (bool) $this->tokens()->delete();
    }
}
