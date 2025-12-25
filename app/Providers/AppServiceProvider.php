<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
         $this->app->bind(\App\Repositories\Cart\CartRepository::class, \App\Repositories\Cart\CartModelRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Set custom temp directory to avoid /tmp permission issues
        $customTempDir = storage_path('framework/tmp');
        if (!is_dir($customTempDir)) {
            @mkdir($customTempDir, 0775, true);
        }
        
        // Set PHP temp directory if not already set
        if (ini_get('sys_temp_dir') === '' || ini_get('sys_temp_dir') === false) {
            @ini_set('sys_temp_dir', $customTempDir);
        }
    }
}
