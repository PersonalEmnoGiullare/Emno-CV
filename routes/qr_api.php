<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Qr\Api\CodigoQrController;

// Rutas que no requieren autentificacion
Route::prefix('qr/api')->name('qr.api')->group(function () {
    // endpoint para obtener todos los listados del usuario autenticado
    Route::post('/cqc/listar', [CodigoQrController::class, 'listarCodigos'])->name('listarCodigos');

    // endpoint para generar un nuevo codigo qr
    Route::post('/cqc/generar', [CodigoQrController::class, 'generarCodigo'])->name('generarCodigo');

    // endpoint para validar un codigo (desde dispositivos IoT)
    Route::post('/cqc/validar', [CodigoQrController::class, 'verificarCodigo'])->name('verificarCodigo');
});
