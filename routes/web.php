<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientMonitoringWebController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MonitoringWebController;
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

    Route::controller(ClientMonitoringWebController::class)->group(function () {
        Route::get('/monitoringweb/website', 'index')->name('clientwebsitemonitoring.index');
    });
});
