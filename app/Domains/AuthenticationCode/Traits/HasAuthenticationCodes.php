<?php

declare(strict_types=1);

namespace App\Domains\AuthenticationCode\Traits;

use App\Domains\AuthenticationCode\Models\AuthenticationCode;
use Eloquent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @property Collection<AuthenticationCode> $authenticationCodes
 *
 * @mixin Eloquent
 */
interface HasAuthenticationCodes
{
    /**
     * @return MorphMany<AuthenticationCode>
     */
    public function authenticationCodes(): MorphMany;
}
