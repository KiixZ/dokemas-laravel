<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RehashUserPassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:rehash-password';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rehash all user passwords in the database';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Your code to rehash user passwords here...
        return 0;
    }
}

