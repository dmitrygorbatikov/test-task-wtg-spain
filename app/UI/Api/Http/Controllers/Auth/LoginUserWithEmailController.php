<?php

declare(strict_types=1);

namespace App\UI\Api\Http\Controllers\Auth;

use App\UI\Api\Http\Requests\Auth\LoginUserByEmailRequest;
use App\UI\Api\Http\Resources\User\Me\UserItemResource;
use App\Application\Auth\Actions\LoginUserByEmailAction;
use App\Domains\Auth\Exceptions\InvalidLoginCredentialsException;
use App\Domains\Auth\Exceptions\UserRegistrationUnfinishedException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\JsonResponse;
use App\Infrastructure\Exceptions\{AccountUnavailableException, ThrottleException};
use App\Infrastructure\Http\Controllers\Controller;

class LoginUserWithEmailController extends Controller
{
    /**
     * @throws UserRegistrationUnfinishedException
     * @throws AccountUnavailableException
     * @throws InvalidLoginCredentialsException
     */
    public function __invoke(
        LoginUserByEmailRequest $request,
        LoginUserByEmailAction $loginAction,
    ): JsonResponse {
        try {
            $user = $loginAction->execute($request->email, $request->password);
        } catch (ThrottleException $e) {
            throw new ThrottleRequestsException(__('api::errors.too_many_requests.message', [
                'time' => $e->getTime(),
            ]));
        }

        return response()->json([
            'token' => $user->createPlainTextToken(),
            'user' => new UserItemResource($user),
        ]);
    }
}
