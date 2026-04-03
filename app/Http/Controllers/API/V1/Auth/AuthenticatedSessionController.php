<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthenticatedSessionController extends Controller
{
    public function me(Request $request): JsonResponse
    {
        $user = $request->user();
        $roles = $user?->getRoleNames() ?? collect();

        return response()->json([
            'data' => [
                'user' => $user,
                'roles' => $roles->values(),
                'role' => $roles->first(),
                'church_id' => $user?->church_id,
            ],
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()?->currentAccessToken()?->delete();

        return response()->json([
            'message' => 'Logout successful',
        ]);
    }
}
