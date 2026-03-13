<?php

declare(strict_types=1);

namespace App\Domains\User\Database\Factories;

use App\Domains\User\Enums\UserStatusEnum;
use App\Domains\User\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;
    private static ?UserStatusEnum $status = null;

    public function definition(): array
    {
        return [
            'status' => $status = self::$status ?? $this->getRandomStatus(),
            'first_name' => $firstName = $this->faker->firstName(),
            'last_name' => $lastName = $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/',
            'created_at' => $this->faker->dateTimeBetween(now()->subYears(5), now()->subMonth()),
        ];
    }

    public function active(): static
    {
        self::$status = UserStatusEnum::Active;

        return $this;
    }

    public function byStatus(UserStatusEnum ...$statuses): static
    {
        self::$status = $this->faker->randomElement($statuses);

        return $this;
    }

    public function withSocials(bool $setGoogle = true): static
    {
        return $this->state(fn () => [
            'google_id' => $setGoogle ? Str::uuid()->toString() : null,
        ]);
    }

    public function withEmail(string $email): static
    {
        return $this->state(fn () => [
            'email' => $email,
        ]);
    }

    private function getRandomStatus(?UserStatusEnum $except = null): UserStatusEnum
    {
        $exceptStatuses = UserStatusEnum::getRemovedStatuses();

        if ($except) {
            $exceptStatuses[] = $except;
        }

        return $this->faker->randomElement(UserStatusEnum::except($exceptStatuses));
    }
}
