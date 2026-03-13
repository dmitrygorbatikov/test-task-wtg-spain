<?php

declare(strict_types=1);

namespace App\UI\Api\Http\Controllers\Auth;

use App\Application\Auth\Actions\LogoutUserAction;
use App\Domains\User\Models\User;
use App\Infrastructure\Http\Controllers\Controller;
use App\UI\Api\Http\Requests\BaseRequest;
use Illuminate\Http\Response;

class LogoutUserController extends Controller
{
    public function __invoke(BaseRequest $request, LogoutUserAction $logoutUserAction): Response
    {
        /** @var User $user */
        $user = $request->user();

        $logoutUserAction->execute($user);

        return response()->noContent();
    }
}
