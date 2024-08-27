<?php

namespace App\Providers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\ServiceProvider;

class MonitorServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->terminating(function () {
            $this->runCommand();
        });
    }

    protected function runCommand()
    {
        Artisan::call('monitor:resources');
        sleep(5);
        $this->runCommand();
    }

    public function register()
    {
        //
    }
}
