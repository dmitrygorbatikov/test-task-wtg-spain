<?php

declare(strict_types=1);

namespace App\Domains\User\Models;

use Carbon\Carbon;
use App\Domains\AuthenticationCode\Models\AuthenticationCode;
use App\Domains\AuthenticationCode\Traits\HasAuthenticationCodes;
use App\Domains\User\Database\Factories\UserFactory;
use App\Domains\User\Enums\UserStatusEnum;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use App\Infrastructure\Models\AuthenticatableModel;

/**
 * @property int $id
 * @property UserStatusEnum $status
 * @property string $first_name
 * @property string $last_name
 * @property string $name
 * @property string $slug
 * @property string $email
 * @property ?Carbon $email_verified_at
 * @property string $password
 * @property ?Carbon $created_at
 * @property ?Carbon $updated_at
 * @property ?Carbon $deleted_at
 *
 * @method static UserFactory factory($count = null, $state = [])
 */
class User extends AuthenticatableModel implements
    HasAuthenticationCodes
{
    use HasFactory;
    use Notifiable;
    use SoftDeletes;


    protected $guarded = ['id'];

    protected $hidden = ['password'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'status' => UserStatusEnum::class,
    ];


    public function password(): Attribute
    {
        return Attribute::set(static fn ($value) => Hash::make($value));
    }

    public function authenticationCodes(): MorphMany
    {
        return $this->morphMany(AuthenticationCode::class, 'authenticatable');
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function hasStatus(UserStatusEnum ...$statuses): bool
    {
        return in_array($this->status, $statuses, true);
    }

    public function hasActiveStatus(): bool
    {
        return $this->status === UserStatusEnum::Active;
    }

    public function hasDraftStatus(): bool
    {
        return $this->status === UserStatusEnum::Draft;
    }

    public function hasNotActiveStatus(): bool
    {
        return false;
    }

    public function hasRemovedStatus(): bool
    {
        return $this->status === UserStatusEnum::Removed;
    }

    public function hasDeletedStatus(): bool
    {
        return $this->hasStatus(...UserStatusEnum::getRemovedStatuses());
    }

    public function hasBlockedStatus(): bool
    {
        return $this->hasStatus(...UserStatusEnum::getBlockedStatuses());
    }

    public function wasActiveBefore(): bool
    {
        return $this->getOriginal('status') === UserStatusEnum::Active;
    }

    public function wasRemoverByUserBefore(): bool
    {
        return $this->getOriginal('status') === UserStatusEnum::RemovedByUser;
    }

    public function createPlainTextToken(): string
    {
        $token = $this->createToken('auth', [config('auth.access_abilities.api')]);

        return preg_replace('/^(\d+\|)/', '', $token->plainTextToken);
    }

    public function getNestedAuthByEntity(): null|User
    {
        return $this->getCurrentPersonalAccessToken()?->authByOtherEntity;
    }

    public function countComplainAboutMe(): Attribute
    {
        return Attribute::make(get: fn ($value) => $this->complainAboutMe()->count());
    }

    public static function getSearchableColumns(): array
    {
        return [
            'id',
            'status',
        ];
    }

    public static function getSearchableRelations(): array
    {
        return [
            'image:id,model_id,model_type,collection_name',
            'customer.latestSubscription',
            'country:id,title,iso_2_code',
            'position:id,title',
        ];
    }

    public function toSearchableArray(): array
    {
        $subscription = $this->customer?->latestSubscription;

        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'status' => $this->status->value,
        ];
    }

    public function customShouldBeSearchable(): bool
    {
        return UserStatusEnum::has($this->status, UserStatusEnum::getNotRemovedStatuses());
    }

    public function customWasSearchableBeforeUpdate(): bool
    {
        return $this->wasChanged(['status']);
    }

    public function customWasSearchableBeforeDelete(): bool
    {
        return UserStatusEnum::has($this->status, UserStatusEnum::getNotRemovedStatuses());
    }

    public function hasVerified(): bool
    {
        return (bool) $this->email_verified_at;
    }

    protected static function newFactory(): UserFactory
    {
        return new UserFactory();
    }
}
