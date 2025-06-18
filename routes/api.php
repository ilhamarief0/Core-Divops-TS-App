<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\ForumRecapsApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::prefix('forum-recaps')->group(function () {
        Route::get('/weekly', [ForumRecapsApiController::class, 'getRecapData'])->name('api.weeklyrecaps')->defaults('type', 'weekly');
        Route::get('/monthly', [ForumRecapsApiController::class, 'getRecapData'])->name('api.monthlyrecaps')->defaults('type', 'monthly');
    });
});
