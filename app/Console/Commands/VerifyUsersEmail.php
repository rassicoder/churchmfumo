<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class VerifyUsersEmail extends Command
{
    protected $signature = 'users:verify-email {--force : Verify all users even if already verified}';

    protected $description = 'Mark users as email-verified if email_verified_at is null.';

    public function handle(): int
    {
        $query = User::query();
        if (! $this->option('force')) {
            $query->whereNull('email_verified_at');
        }

        $count = $query->update(['email_verified_at' => now()]);
        $this->info('Verified users updated: ' . $count);

        return self::SUCCESS;
    }
}
