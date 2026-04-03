<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return response()->json([
                'message' => 'Unauthenticated.',
            ], 401);
        }

        $normalizedRoles = array_values(array_filter(array_map(static fn (string $role): string => trim($role), $roles)));

        if ($normalizedRoles === []) {
            return response()->json([
                'message' => 'No roles were configured for this route.',
            ], 500);
        }

        if (! $user->hasAnyRole($normalizedRoles)) {
            return response()->json([
                'message' => 'Forbidden. Your role cannot access this resource.',
                'required_roles' => $normalizedRoles,
            ], 403);
        }

        return $next($request);
    }
}
