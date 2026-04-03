<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $name = env('ADMIN_NAME', 'Super Admin');
        $email = env('ADMIN_EMAIL', 'admin@church.com');
        $password = env('ADMIN_PASSWORD', 'password123');

        $existing = User::query()->where('email', $email)->first();
        if ($existing) {
            $existing->forceFill([
                'name' => $name,
                'password' => Hash::make($password),
                'email_verified_at' => now(),
            ])->save();

            if (! $existing->hasRole('Super Admin')) {
                $existing->assignRole('Super Admin');
            }

            return;
        }

        /** @var User $user */
        $user = User::query()->create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'email_verified_at' => now(),
        ]);

        $user->assignRole('Super Admin');
    }
}
