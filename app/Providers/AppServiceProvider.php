<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        try {
            $disk = Storage::disk('public');
            
            // Create necessary directories if they don't exist
            $directories = [
                'destinations',
                'destinations/gallery'
            ];

            foreach ($directories as $dir) {
                if (!$disk->exists($dir)) {
                    $disk->makeDirectory($dir);
                    Log::info("Created directory: {$dir}");
                }
            }

            // Verify directories are writable
            foreach ($directories as $dir) {
                $fullPath = storage_path("app/public/{$dir}");
                if (!is_writable($fullPath)) {
                    Log::warning("Directory not writable: {$dir}");
                }
            }
        } catch (\Exception $e) {
            Log::error('Failed to initialize storage directories', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}

