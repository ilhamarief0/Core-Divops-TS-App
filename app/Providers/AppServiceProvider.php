<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Dusk\DuskServiceProvider;

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
        if ($this->app->environment('development')) {
            \URL::forceScheme('https');
        }
        if ($this->app->environment('local', 'testing')) {
          $this->app->register(DuskServiceProvider::class);
      }
    }
}
