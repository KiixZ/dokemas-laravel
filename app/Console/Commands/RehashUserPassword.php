<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RehashUserPasswords extends Command
{
    protected $signature = 'users:rehash-passwords';
    protected $description = 'Rehash all user passwords in the database';

    public function handle()
    {
        $users = User::all();
        $bar = $this->output->createProgressBar(count($users));

        $this->info('Rehashing user passwords...');

        foreach ($users as $user) {
            if (!Hash::needsRehash($user->password)) {
                $user->password = $user->password; // This will trigger the setPasswordAttribute method
                $user->save();
            }
            $bar->advance();
        }

        $bar->finish();
        $this->info("\nAll user passwords have been rehashed.");
    }
}

