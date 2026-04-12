<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function __construct(private readonly AuthService $authService)
    {
    }

    public function showAdminLogin(Request $request): View|RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return view('auth.admin-login');
    }

    public function showChurchLogin(Request $request): View|RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return view('auth.church-login');
    }

    public function adminLogin(Request $request): JsonResponse|RedirectResponse|View
    {
        return $this->attemptRoleLogin(
            $request,
            'Super Admin',
            '/admin/dashboard',
            'Only Super Admin accounts can sign in here.'
        );
    }

    public function churchLogin(Request $request): JsonResponse|RedirectResponse|View
    {
        return $this->attemptRoleLogin(
            $request,
            'Church Admin',
            '/admin/church-dashboard',
            'Only Church Admin accounts can sign in here.'
        );
    }

    private function attemptRoleLogin(
        Request $request,
        string $requiredRole,
        string $redirectPath,
        string $roleMismatchMessage
    ): JsonResponse|RedirectResponse|View {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        try {
            $user = $this->authService->login(
                strtolower($credentials['email']),
                $credentials['password']
            );
        } catch (ValidationException $exception) {
            return $this->failedLoginResponse($request, $exception->errors());
        }

        if (! $user->hasRole($requiredRole)) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return $this->failedLoginResponse($request, [
                'email' => [$roleMismatchMessage],
            ], 403);
        }

        if (! $user->hasVerifiedEmail()) {
            $user->forceFill([
                'email_verified_at' => now(),
            ])->save();
        }

        $token = $user->createToken('web-login')->plainTextToken;

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Login successful.',
                'data' => [
                    'user' => $user,
                    'token' => $token,
                    'redirect_to' => $redirectPath,
                ],
            ]);
        }

        return view('auth.login-redirect', [
            'token' => $token,
            'user' => $user,
            'role' => $requiredRole,
            'redirectPath' => $redirectPath,
        ]);
    }

    private function failedLoginResponse(
        Request $request,
        array $errors,
        int $status = 422
    ): JsonResponse|RedirectResponse {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => $errors['email'][0] ?? 'Login failed.',
                'errors' => $errors,
            ], $status);
        }

        return back()
            ->withErrors($errors)
            ->withInput($request->only('email'));
    }
}
