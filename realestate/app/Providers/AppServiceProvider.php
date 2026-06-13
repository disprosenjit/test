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
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(\App\Services\SettingsService $settings): void
    {
        $activeTheme = $settings->get('active_theme', 'default');
        $themePath = resource_path("views/themes/{$activeTheme}");

        if (is_dir($themePath)) {
            \Illuminate\Support\Facades\View::getFinder()->prependLocation($themePath);
        }
    }
}
