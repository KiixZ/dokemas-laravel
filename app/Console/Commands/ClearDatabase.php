<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Category;
use App\Models\Article;
use App\Models\Destination;
use Illuminate\Support\Facades\DB;

class ClearDatabase extends Command
{
    protected $signature = 'db:clear {--force : Force the operation to run}';
    protected $description = 'Clear all data from users, categories, articles, and destinations tables';

    public function handle()
    {
        if (!$this->option('force') && !$this->confirm('PERINGATAN: Ini akan menghapus SEMUA data dari database. Apakah Anda yakin ingin melanjutkan?')) {
            $this->info('Operasi dibatalkan.');
            return;
        }

        $this->info('Memulai proses penghapusan data...');

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $this->clearTable(User::class, 'users');
        $this->clearTable(Category::class, 'categories');
        $this->clearTable(Article::class, 'articles');
        $this->clearTable(Destination::class, 'destinations');

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->info('Semua data telah berhasil dihapus.');
    }

    private function clearTable($model, $tableName)
    {
        $count = $model::count();
        $model::truncate();
        $this->info("Tabel {$tableName}: {$count} record dihapus.");
    }
}

