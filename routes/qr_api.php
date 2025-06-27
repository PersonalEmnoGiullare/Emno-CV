<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Qr\Api\CodigoQrController;

// definimos las rutas que no requieren autenticacion
Route::prefix('qr/api')->name('qr.api')->group(function () {
    // endpoint para obtener todos los listados del usuario autenticado
    Route::get('/cqc', [CodigoQrController::class, 'listarCodigos'])->name('listarCodigos');

    // endpoint para generar un nuevo codigo qr
    Route::post('/cqc', [CodigoQrController::class, 'generarCodigo'])->name('generarCodigo');

    // endpoint para validar un codigo 
    Route::post('/cqc/validar', [CodigoQrController::class, 'verificarCodigo'])->name('verificarCodigo');
});
