<?php

declare(strict_types=1);

namespace App\Domains\PersonalAccessToken\Models;

use Carbon\Carbon;
use App\Domains\User\Database\Factories\UserFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use App\Infrastructure\Models\AuthenticatableModel;
use Laravel\Sanctum\{HasApiTokens, PersonalAccessToken as SanctumPersonalAccessToken};

/**
 * @property int $id
 * @property string $name
 * @property string $token
 * @property string $tokenable_type
 * @property int $tokenable_id
 * @property ?int $auth_by_id
 * @property ?string $auth_by_type
 * @property array $abilities
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property ?Carbon $last_used_at
 * @property ?Carbon $expires_at
 * @property AuthenticatableModel $tokenable
 *
 * @mixin Eloquent
 */
class PersonalAccessToken extends SanctumPersonalAccessToken
{
    use HasApiTokens;

    protected $fillable = [
        'name',
        'token',
        'tokenable_type',
        'tokenable_id',
        'abilities',
        'expires_at',
        'auth_by_type',
        'auth_by_id',
    ];

    public function authByOtherEntity(): MorphTo
    {
        return $this->morphTo('auth_by');
    }

    protected function lastUsedAt(): Attribute
    {
        // We need to set it to the same value as the original so the Sanctum's `UPDATE` won't execute on each request
        return Attribute::make(set: fn () => $this->getOriginal('last_used_at'));
    }
}
