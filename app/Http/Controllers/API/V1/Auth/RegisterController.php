<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;

class RegisterController extends Controller
{
    public function __construct(private readonly AuthService $authService)
    {
    }

    public function __invoke(RegisterRequest $request): JsonResponse
    {
        $user = $this->authService->register($request->validated());
        $user->sendEmailVerificationNotification();

        $token = $user->createToken($request->string('device_name', 'api-token')->toString())->plainTextToken;

        return response()->json([
            'message' => 'Registration successful. Verification email sent.',
            'data' => [
                'user' => $user,
                'token' => $token,
                'redirect_to' => $this->authService->resolveRedirectPath($user),
            ],
        ], 201);
    }
}
