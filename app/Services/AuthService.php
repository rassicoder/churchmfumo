<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Exceptions\RoleDoesNotExist;

class AuthService
{
    public function __construct(private readonly UserRepositoryInterface $users)
    {
    }

    public function register(array $data): User
    {
        $defaultRole = config('rbac.default_registration_role', 'Secretary');
        $selectedRole = $data['role'] ?? $defaultRole;

        $allowedRoles = config('rbac.roles', []);
        if (! in_array($selectedRole, $allowedRoles, true)) {
            throw ValidationException::withMessages([
                'role' => ['Invalid role selected.'],
            ]);
        }

        if ($selectedRole !== $defaultRole) {
            $actor = Auth::user();
            $assigners = config('rbac.elevated_role_assigners', ['Super Admin']);

            if (! $actor || ! $actor->hasAnyRole($assigners)) {
                throw ValidationException::withMessages([
                    'role' => ['You are not authorized to assign this role.'],
                ]);
            }
        }

        /** @var User $user */
        $user = $this->users->create([
            'name' => $data['name'],
            'email' => strtolower($data['email']),
            'password' => $data['password'],
        ]);

        try {
            $user->assignRole($selectedRole);
        } catch (RoleDoesNotExist) {
            throw ValidationException::withMessages([
                'role' => ['Configured role does not exist. Run database seeders.'],
            ]);
        }

        return $user;
    }

    public function login(string $email, string $password): User
    {
        /** @var User|null $user */
        $user = $this->users->findByEmail($email);

        if (! $user || ! Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid login credentials.'],
            ]);
        }

        return $user;
    }

    public function resolveRedirectPath(User $user): string
    {
        foreach (config('auth_redirects.roles', []) as $role => $path) {
            if ($user->hasRole($role)) {
                return $path;
            }
        }

        return config('auth_redirects.default', '/dashboard');
    }
}
