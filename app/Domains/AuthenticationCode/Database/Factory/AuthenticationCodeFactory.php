<?php

declare(strict_types=1);

namespace App\Domains\AuthenticationCode\Database\Factory;

use Carbon\Carbon;
use App\Domains\AuthenticationCode\Enums\AuthenticationCodePurpose;
use App\Domains\AuthenticationCode\Models\AuthenticationCode;
use App\Domains\AuthenticationCode\Traits\HasAuthenticationCodes;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Infrastructure\Models\Model;

/**
 * @extends Factory<AuthenticationCode>
 */
class AuthenticationCodeFactory extends Factory
{
    protected $model = AuthenticationCode::class;
    private static ?AuthenticationCodePurpose $purpose = null;

    public function definition(): array
    {
        return [
            'code' => (string) $this->faker->randomNumber(4),
            'identifier' => $this->faker->uuid(),
            'purpose' => self::$purpose ?? $this->faker->randomElement(AuthenticationCodePurpose::cases()),
        ];
    }

    public function forAuthenticatable(HasAuthenticationCodes $authenticatable): self
    {
        /** @var HasAuthenticationCodes&Model $authenticatable */
        return $this->state(fn () => [
            'authenticatable_id' => $authenticatable->getKey(),
            'authenticatable_type' => $authenticatable->getMorphClass(),
        ]);
    }

    public function forPurpose(AuthenticationCodePurpose $purpose): self
    {
        self::$purpose = $purpose;

        return $this;
    }

    public function forCode(string $code): self
    {
        return $this->state(fn () => [
            'code' => $code,
        ]);
    }

    public function forEmailCode(): self
    {
        return $this->state(fn () => [
            'code' => config('mail.test_users_email_verification_code'),
        ]);
    }

    public function forIdentifier(string $uuid): self
    {
        return $this->state(fn () => [
            'identifier' => $uuid,
        ]);
    }

    public function created(?Carbon $date = null): self
    {
        $date ??= now();

        return $this->state(fn () => [
            'created_at' => $date,
            'updated_at' => $date,
        ]);
    }
}
