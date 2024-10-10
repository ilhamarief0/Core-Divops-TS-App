<?php

use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientMonitoringWebController;
use App\Http\Controllers\ClientWebsiteMonitoringWebController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MonitoringWebController;
use App\Http\Controllers\ServerDevResourceMonitoringController;
use App\Http\Controllers\WeeklyRecapsForumController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;



Route::middleware('guest')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::get('/login', 'loginview')->name('login');
        Route::post('/login-post', 'ajaxLogin')->name('login.post');
    });
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/', 'index')->name('dashboard.index');
    });

    Route::controller(MonitoringWebController::class)->group(function () {
        Route::get('/monitoringweb', 'index')->name('monitoringweb.index');
    });

    Route::controller(ClientMonitoringWebController::class)->group(function () {
        Route::get('/monitoringweb/client', 'index')->name('clientmonitoringweb.index');
        Route::post('/monitoringweb/client/store', 'store')->name('clientmonitoringweb.store');
        Route::delete('/monitoringweb/client/delete/{id}', 'delete')->name('clientmonitoringweb.delete');
        Route::get('/monitoringweb/client/getData/{id}', 'getData')->name('clientmonitoringweb.getdata');
        Route::post('/monitoringweb/client/update/{id}', 'update')->name('clientmonitoring.update');
    });

    Route::controller(ClientWebsiteMonitoringWebController::class)->group(function () {
        Route::get('/monitoringweb/website', 'index')->name('clientwebsitemonitoring.index');
        Route::post('/monitoringweb/website/store', 'store')->name('clientwebsitemonitoring.store');
        Route::delete('/monitoringweb/website/delete/{id}', 'delete')->name('clientwebsitemonitroing.delete');
        Route::get('/monitoringweb/website/getData/{id}', 'getData')->name('clientwebsitemonitoring.getdata');
        Route::post('/monitoringweb/website/update/{id}', 'update')->name('clientwebsitemonitoring.update');
        Route::get('/monitoringweb/website/show/{id}', 'show')->name('clientwebsitemonitoring.show');
    });

    Route::controller(WeeklyRecapsForumController::class)->group(function () {
        Route::get('/forum/weeklyrecaps', 'index')->name('weeklyrecaps.index');
    });

    Route::controller(ServerDevResourceMonitoringController::class)->group(function () {
        Route::get('/monitoringserver/serverdev', 'index')->name('sereverdev.index');
    });
});



Route::get('/api/server-resources', function (Request $request) {
    // Ambil data resource server terbaru
    $data = [
        [
            'category' => 'Disk Usage',
            'value' => DB::table('server_dev_resources')->latest()->value('disk_usage'),
            'full' => 100,
            'columnSettings' => ['fill' => '#67b7dc']
        ],
        [
            'category' => 'Memory Usage',
            'value' => DB::table('server_dev_resources')->latest()->value('memory_usage'),
            'full' => 100,
            'columnSettings' => ['fill' => '#6794dc']
        ],
        [
            'category' => 'CPU Usage',
            'value' => DB::table('server_dev_resources')->latest()->value('cpu_usage'),
            'full' => 100,
            'columnSettings' => ['fill' => '#dc67ab']
        ]
    ];
    return response()->json(['data' => $data]);
});
