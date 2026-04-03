<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleBasedRedirect
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        $user = $request->user();

        if (! $user || ! method_exists($response, 'headers')) {
            return $response;
        }

        $response->headers->set('X-Redirect-To', $this->resolveRedirectPath($user));

        return $response;
    }

    private function resolveRedirectPath(object $user): string
    {
        foreach (config('auth_redirects.roles', []) as $role => $path) {
            if (method_exists($user, 'hasRole') && $user->hasRole($role)) {
                return $path;
            }
        }

        return config('auth_redirects.default', '/dashboard');
    }
}
