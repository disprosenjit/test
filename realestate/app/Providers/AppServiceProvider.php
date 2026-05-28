<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

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
    public function boot(): void
    {
        View::composer('*', function ($view): void {
            $supportedLocales = config('app.supported_locales', []);

            $view->with('supportedLocales', $supportedLocales)
                ->with('currentLocale', app()->getLocale())
                ->with('isRtl', app()->getLocale() === 'ar');
        });
    }
}
