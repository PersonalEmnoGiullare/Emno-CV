<?php

use App\Http\Controllers\Qr\PortalQrController;
use Illuminate\Support\Facades\Route;

// definimos las rutas que requieren autenticación y un rol de usuario admin o superadmin
Route::middleware([
    'auth:sanctum',
    'rol:qr_user-qr_admin-SuperAdmin'
])->group(function () {
    // ruta para generar un código qr
    Route::get('/qr', [PortalQrController::class, 'generarQr'])->name('qr.generar');
    // ruta para generar un código qr
    Route::get('/qr/generar', [PortalQrController::class, 'generarQr'])->name('qr.generar');
    // ruta para consultar códigos qr
    Route::get('/qr/consultar', [PortalQrController::class, 'consultarQr'])->name('qr.consultar');
});

// definimos las rutas que requieren autenticación y un rol de superadmin o admin
// Route::middleware(['auth', 'rol:qr_admin-SuperAdmin'])->group(function () {});
