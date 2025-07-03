<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Qr\Api\CodigoQrController;
use App\Http\Middleware\UserRol;

// Rutas protegidas para web y móvil
Route::prefix('qr/api')->middleware(['auth:sanctum', 'rol:qr_admin-qr_user'])->name('qr.api')->group(function () {
    // endpoint para obtener todos los listados del usuario autenticado
    Route::post('/cqc/listar', [CodigoQrController::class, 'listarCodigos'])->name('listarCodigos');

    // endpoint para generar un nuevo codigo qr
    Route::post('/cqc/generar', [CodigoQrController::class, 'generarCodigo'])->name('generarCodigo');
});

// Ruta para dispositivos IoT (solo con token válido)
Route::prefix('qr/api')->middleware(['auth:sanctum', 'rol:qr_admin'])->group(function () {
    // Validar código - solo para dispositivos IoT
    Route::post('/cqc/validar', [CodigoQrController::class, 'verificarCodigo'])->name('verificarCodigo');
});
