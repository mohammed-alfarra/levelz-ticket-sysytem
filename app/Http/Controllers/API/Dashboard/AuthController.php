<?php

namespace App\Http\Controllers\API\Dashboard;

use App\Enums\Guards;
use App\Http\Controllers\API\BaseAuthController;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\Admin\AdminResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class AuthController extends BaseAuthController
{
    protected string $guard = Guards::ADMIN;

    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        if (! $token = auth(Guards::ADMIN)->attempt($credentials)) {
            throw new UnprocessableEntityHttpException(trans('auth.incorrect_email_password'));
        }

        if (! auth(Guards::ADMIN)->user()->isActiveAccount()) {
            throw new UnprocessableEntityHttpException(trans('auth.deactivated_account'));
        }

        return $this->respondWithToken($token);
    }

    public function userTransformer(): JsonResource
    {
        $user = auth($this->guard)
            ->user();

        return new AdminResource($user);
    }
}
