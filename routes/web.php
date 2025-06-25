<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// definimos las rutas que no requieren autenticacion

// definimos las rutas que requieren solo estar autenticado
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// definimos las rutas que requieren autenticacion y un rol especifico


// definimos las rutas que requieren no estar autenticado
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});
