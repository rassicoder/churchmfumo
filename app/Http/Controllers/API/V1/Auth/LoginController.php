<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    private const MAX_ATTEMPTS = 5;
    private const DECAY_SECONDS = 900;

    public function __construct(private readonly AuthService $authService)
    {
    }

    public function __invoke(LoginRequest $request): JsonResponse
    {
        $email = $request->string('email')->lower()->toString();
        $key = $this->throttleKey($email, (string) $request->ip());

        if (RateLimiter::tooManyAttempts($key, self::MAX_ATTEMPTS)) {
            $seconds = RateLimiter::availableIn($key);

            return response()->json([
                'message' => 'Account temporarily locked due to too many failed attempts.',
                'errors' => [
                    'email' => ['Too many login attempts. Try again later.'],
                ],
                'retry_after_seconds' => $seconds,
            ], 423);
        }

        try {
            $user = $this->authService->login($email, $request->string('password')->toString());
        } catch (ValidationException $exception) {
            RateLimiter::hit($key, self::DECAY_SECONDS);
            throw $exception;
        }

        RateLimiter::clear($key);

        $token = $user->createToken($request->string('device_name', 'api-token')->toString())->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'data' => [
                'user' => $user,
                'token' => $token,
                'redirect_to' => $this->authService->resolveRedirectPath($user),
            ],
        ]);
    }

    private function throttleKey(string $email, string $ip): string
    {
        return sprintf('login:%s|%s', $email, $ip);
    }
}
