<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CheckAndFixUserPasswords extends Command
{
    protected $signature = 'users:check-fix-passwords';
    protected $description = 'Check and fix user passwords that might not be hashed correctly';

    public function handle()
    {
        $users = User::all();
        $fixedCount = 0;

        foreach ($users as $user) {
            if (Hash::needsRehash($user->password)) {
                // We'll set a temporary password and then hash it
                $tempPassword = 'temp_' . Str::random(10);
                $user->password = $tempPassword;
                $user->save();

                $this->info("Fixed password for user ID: {$user->id}");
                $fixedCount++;
            }
        }

        $this->info("Checked {$users->count()} users. Fixed {$fixedCount} passwords.");
        
        if ($fixedCount > 0) {
            $this->warn("Some passwords were reset. Users with reset passwords will need to use the 'Forgot Password' feature to set a new password.");
        }
    }
}

