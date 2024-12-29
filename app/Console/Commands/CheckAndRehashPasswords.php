<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CheckAndRehashPasswords extends Command
{
    protected $signature = 'users:check-rehash-passwords';
    protected $description = 'Check and rehash user passwords if necessary';

    public function handle()
    {
        $users = User::all();
        $rehashed = 0;

        foreach ($users as $user) {
            if (Hash::needsRehash($user->password)) {
                $user->password = $user->password;
                $user->save();
                $rehashed++;
            }
        }

        $this->info("Checked {$users->count()} users. Rehashed {$rehashed} passwords.");
    }
}

