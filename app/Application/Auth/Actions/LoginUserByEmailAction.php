<?php

declare(strict_types=1);

namespace App\Application\Auth\Actions;

use App\Domains\Auth\Exceptions\{InvalidLoginCredentialsException, UserRegistrationUnfinishedException};
use Arr;
use App\Domains\User\Eloquent\UserEloquent;
use App\Domains\User\Enums\UserStatusEnum;
use App\Domains\User\Exceptions\UserFindException;
use App\Domains\User\Models\User;
use Hash;
use Illuminate\Support\Facades\RateLimiter;
use App\Infrastructure\Exceptions\{AccountUnavailableException, ThrottleException};

class LoginUserByEmailAction
{
//    private string $rateLimitKey;
//    private string $rateLimitConfigKey = 'auth_routes_with_email';
//    private int $maxAttempts;
//    private int $decayMinutes;

    public function __construct(
        private readonly UserEloquent $userEloquent,
        private readonly CheckUserAccountIsAvailableAction $checkUserAccountIsAvailableAction,
    ) {
//        $rateLimitConfig = config("rate-limiting.api.{$this->rateLimitConfigKey}");
//
//        $rateLimitConfig = empty($rateLimitConfig) ? config('rate-limiting.default') : $rateLimitConfig;
//
//        $this->maxAttempts = Arr::get($rateLimitConfig, 'maxAttempts');
//        $this->decayMinutes = Arr::get($rateLimitConfig, 'decayMinutes');
    }

    /**
     * @throws AccountUnavailableException
     * @throws UserRegistrationUnfinishedException
     * @throws InvalidLoginCredentialsException
//     * @throws ThrottleException
     */
    public function execute(string $email, string $password): User
    {
//        $this->rateLimitKey = "{$this->rateLimitConfigKey}:{$email}";

//        $this->checkRateLimit();
//        $this->hitRateLimit();

        try {
            $user = $this->userEloquent->getByEmail($email, ['*'], ...UserStatusEnum::except(UserStatusEnum::Draft));
        } catch (UserFindException) {
            throw new InvalidLoginCredentialsException();
        }

        if (!Hash::check($password, $user->password)) {
            throw new InvalidLoginCredentialsException();
        }

        if ($user->hasStatus(UserStatusEnum::RemovedByUser)) {
            return $user;
        }

        $this->clearInactiveUserTokens($user);

        $this->checkUserAccountIsAvailableAction->execute($user);

//        $this->clearRateLimit();

        return $user;
    }

    private function clearInactiveUserTokens(User $user): void
    {
        if (!$user->hasStatus(UserStatusEnum::Active, UserStatusEnum::ProfileNotCompleted)) {
            $user->clearTokens();
        }
    }

//    /**
//     * @throws ThrottleException
//     */
//    private function checkRateLimit(): void
//    {
//        if (RateLimiter::tooManyAttempts($this->rateLimitKey, $this->maxAttempts)) {
//            throw new ThrottleException(RateLimiter::availableIn($this->rateLimitKey));
//        }
//    }
//
//    private function clearRateLimit(): void
//    {
//        RateLimiter::clear($this->rateLimitKey);
//    }
//
//    private function hitRateLimit(): void
//    {
//        RateLimiter::hit($this->rateLimitKey, $this->decayMinutes * 60);
//    }
}
