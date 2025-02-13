<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class BaseAuthController extends Controller
{
    protected string $guard;

    public function __construct()
    {
        $this->middleware('auth:'.$this->guard, [
            'except' => ['login'],
        ]);
    }

    /**
     * Get the authenticated User.
     */
    public function me(): JsonResponse
    {
        return $this->responseSuccess('Logged User data', $this->userTransformer());
    }

    public function logout(): JsonResponse
    {
        auth($this->guard)->logout();

        return $this->responseSuccess('Successfully logged out');
    }

    public function userTransformer(): JsonResource
    {
        $user = auth($this->guard)
            ->user();

        return new UserResource($user);
    }

    /**
     * Get the token array structure.
     */
    protected function respondWithToken($token): JsonResponse
    {
        return $this->responseSuccess('logged user Token', [
            'token' => [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth($this->guard)
                    ->factory()
                    ->getTTL() * 60,
            ],
            'user' => $this->userTransformer(),
        ]);
    }
}
