<?php

declare(strict_types=1);

namespace App\Domains\AuthenticationCode\Models;

use App\Domains\AuthenticationCode\Database\Factory\AuthenticationCodeFactory;
use App\Domains\AuthenticationCode\Enums\AuthenticationCodePurpose;
use App\Domains\AuthenticationCode\Traits\HasAuthenticationCodes;
use Eloquent;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\{Factories\HasFactory};
use Illuminate\Support\Carbon;
use App\Infrastructure\Models\Model;

/**
 * @property int $id
 * @property string $code
 * @property string $identifier
 * @property AuthenticationCodePurpose $purpose
 * @property string $authenticatable_type
 * @property int $authenticatable_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property HasAuthenticationCodes $authenticatable
 *
 * @method static AuthenticationCodeFactory factory(...$parameters)
 *
 * @mixin Eloquent
 */
class AuthenticationCode extends Model
{
    use HasFactory;

    public const TTL_IN_MINUTES = 15;
    public const TTL_IN_MINUTES_FOR_PUBLIC = 30;

    public const EMAIL_CODE_SIZE = 6;

    public const PREFIX_2FA = 'system_user_auth_';

    protected $fillable = ['code', 'identifier', 'purpose', 'authenticatable_type', 'authenticatable_id'];

    protected $casts = [
        'purpose' => AuthenticationCodePurpose::class,
    ];

    public function authenticatable(): MorphTo
    {
        return $this->morphTo();
    }

    public function isExpired(): bool
    {
        return match ($this->purpose) {
            AuthenticationCodePurpose::UserFinishEmailRegistration => false,
            default => $this->created_at->isBefore(now()->subMinutes(self::TTL_IN_MINUTES))
        };
    }

    protected static function newFactory(): AuthenticationCodeFactory
    {
        return AuthenticationCodeFactory::new();
    }
}
