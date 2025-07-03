<?php

use App\Http\Controllers\ApiAuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/login', [ApiAuthController::class, 'login'])->name('api.login');
    Route::middleware('auth:sanctum')->post('/logout', [ApiAuthController::class, 'logout'])->name('api.logout');
});
