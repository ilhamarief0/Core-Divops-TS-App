<?php

use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientMonitoringWebController;
use App\Http\Controllers\ClientWebsiteMonitoringWebController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MonitoringServerController;
use App\Http\Controllers\MonitoringWebController;
use App\Http\Controllers\MonthlyRecapsForumController;
use App\Http\Controllers\OverviewMonitoringController;
use App\Http\Controllers\ServerDevResourceMonitoringController;
use App\Http\Controllers\WeeklyRecapsForumController;
use Illuminate\Support\Facades\Route;


Route::redirect('/', '/login');

Route::controller(MonitoringServerController::class)->group(function(){
    Route::get('/statusserver', 'indexstatusserver')->name('dashboard.monitoringserver');
    Route::get('/check-servers', 'checkServers')->name('check-servers.monitoringserver');
});

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
        Route::get('/monitoringweb/client/dataTable', 'dataTable')->name('clientmonitoringweb.datatable');
        Route::post('/monitoringweb/client/bulk-delete', 'bulkDelete')->name('clientmonitoringweb.bulk-delete');
        Route::post('/monitoringweb/client/store', 'store')->name('clientmonitoringweb.store');
        Route::delete('/monitoringweb/client/delete/{id}', 'delete')->name('clientmonitoringweb.delete');
        Route::get('/monitoringweb/client/getData/{id}', 'getData')->name('clientmonitoringweb.getdata');
        Route::post('/monitoringweb/client/update/{id}', 'update')->name('clientmonitoring.update');
    });

    Route::controller(OverviewMonitoringController::class)->group(function(){
        Route::get('/overviewmonitoring', 'index')->name('overviewmonitoring.index');
    });

    Route::controller(ClientWebsiteMonitoringWebController::class)->group(function () {
        Route::get('/monitoringweb/website', 'index')->name('clientwebsitemonitoring.index');
        Route::get('/monitoringweb/website/dataTable', 'dataTable')->name('clientwebsitemonitoring.datatable');
        Route::post('/monitoringweb/website/bulk-delete', 'bulkDelete')->name('clientwebsitemonitoring.bulk-delete');
        Route::post('/monitoringweb/website/store', 'store')->name('clientwebsitemonitoring.store');
        Route::delete('/monitoringweb/website/delete/{id}', 'delete')->name('clientwebsitemonitroing.delete');
        Route::get('/monitoringweb/website/getData/{id}', 'getData')->name('clientwebsitemonitoring.getdata');
        Route::post('/monitoringweb/website/update/{id}', 'update')->name('clientwebsitemonitoring.update');
        Route::get('/monitoringweb/website/show/{customerSite}', 'show')
            ->name('clientwebsitemonitoring.show')
            ->where('customerSite', '.*');
    });

    Route::controller(WeeklyRecapsForumController::class)->group(function () {
        Route::get('/forum/weeklyrecaps', 'index')->name('weeklyrecaps.index');
    });

    Route::controller(MonthlyRecapsForumController::class)->group(function () {
        Route::get('/forum/monthlyrecaps', 'index')->name('monthlyrecaps.index');
    });

    Route::controller(ServerDevResourceMonitoringController::class)->group(function () {
        Route::get('/monitoringserver/serverdev', 'index')->name('sereverdev.index');
    });
});
