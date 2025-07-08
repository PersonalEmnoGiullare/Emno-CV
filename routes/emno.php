<?php

use App\Http\Controllers\Emno\PortafolioController;
use Illuminate\Support\Facades\Route;

// definimos las rutas que no requieren autenticacion
Route::get('/', function () {
    return view('emno/home');
})->name('home');

Route::get('/portafolio', [PortafolioController::class, 'mostrarPortafolio'])->name('portafolio');


// definimos las rutas que requieren solo estar autenticado
Route::middleware([
    'auth:sanctum'
])->group(function () {
    Route::get('/home', function () {
        return view('emno/home');
    })->name('auth');
});

// definimos las rutas que requieren autenticacion y un rol de admin o superadmin
Route::middleware([
    'auth:sanctum',
    'rol:Admin-SuperAdmin'
])->group(function () {
    Route::get('/admin', function () {
        return view('emno/home');
    })->name('admin');
});

// definimos las rutas que requieren autenticacion y un rol de superadmin
Route::middleware([
    'auth:sanctum',
    'rol:SuperAdmin'
])->group(function () {
    Route::get('/superadmin', function () {
        return view('emno/home');
    })->name('superadmin');
});
