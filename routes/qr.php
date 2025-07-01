<?php

use App\Http\Controllers\Qr\PortalQrController;
use Illuminate\Support\Facades\Route;

// definimos las rutas que no requieren autenticacion

// definimos las rutas que requieren solo estar autenticado
// Route::middleware(['auth'])->group(function () {

// });

// definimos las rutas que requieren autenticacion y un rol de usuario admin o superadmin
Route::middleware(['auth', 'rol:qr_user-qr_admin-SuperAdmin'])->group(function () {
    // ruta para generar un codigo qr
    Route::get('/qr', [PortalQrController::class, 'generarQr'])->name('qr.generar');
    // ruta para generar un codigo qr
    Route::get('/qr/generar', [PortalQrController::class, 'generarQr'])->name('qr.generar');
});

// definimos las rutas que requieren autenticacion y un rol de superadmin o admin
Route::middleware(['auth', 'rol:qr_admin-SuperAdmin'])->group(function () {});
