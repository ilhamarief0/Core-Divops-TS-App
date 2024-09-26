<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::command('app:monitor-website')->everyMinute();

// Schedule::command('notify-user')->everyMinute();

Schedule::command('update:weekly-forum-stats')->fridays()->at('08:00');

// Schedule::command('update:monitoringserver')->everyMinute();
