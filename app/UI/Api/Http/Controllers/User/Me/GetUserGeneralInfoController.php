<?php

declare(strict_types=1);

namespace App\UI\Api\Http\Controllers\User\Me;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use App\Infrastructure\Http\Controllers\Controller;
use App\UI\Api\Http\Requests\BaseRequest;
use App\UI\Api\Http\Resources\User\Me\UserItemResource;

class GetUserGeneralInfoController extends Controller
{
    /**
     * @throws AuthenticationException
     */
    public function __invoke(BaseRequest $request): JsonResponse
    {
        return response()->json(new UserItemResource($request->getAuthByUser()));
    }
}
