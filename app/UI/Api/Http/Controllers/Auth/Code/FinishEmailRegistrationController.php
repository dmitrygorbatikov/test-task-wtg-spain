<?php

declare(strict_types=1);

namespace App\UI\Api\Http\Controllers\Auth\Code;

use App\UI\Api\Http\Resources\User\Me\UserItemResource;
use App\Application\Auth\Actions\Code\FinishRegistrationAction;
use App\Domains\Auth\Exceptions\UserRegistrationAlreadyFinishedException;
use App\Domains\AuthenticationCode\Exceptions\{AuthenticationCodeExpiredException, AuthenticationCodeFindException};
use Illuminate\Http\JsonResponse;
use App\Infrastructure\Exceptions\EntityUpdateException;
use App\Infrastructure\Http\Controllers\Controller;
use App\UI\Api\Http\Requests\Auth\FinishRegistrationRequest;

class FinishEmailRegistrationController extends Controller
{
    /**
     * @throws AuthenticationCodeExpiredException
     * @throws AuthenticationCodeFindException
     * @throws EntityUpdateException
     * @throws UserRegistrationAlreadyFinishedException
     */
    public function __invoke(
        FinishRegistrationRequest $request,
        FinishRegistrationAction $finishRegistrationAction
    ): JsonResponse {
        $user = $finishRegistrationAction->execute($request->getData(), $request->getCodePurpose());

        return response()->json([
            'token' => $user->createPlainTextToken(),
            'user' => new UserItemResource($user),
        ]);
    }
}
