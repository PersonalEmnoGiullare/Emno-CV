<?php

use App\Http\Controllers\RepMetro\EstadisticasAccesos;
use Illuminate\Support\Facades\Route;

// definimos las rutas que requieren autenticacion y un rol de usuario admin o superadmin
Route::middleware([
    'auth:sanctum',
    'rol:rep_metro_empleado-rep_metro_admin-SuperAdmin'
])->prefix('rep_metro')->group(function () {
    // ruta para generar un reporte de estadísticas de accesos
    Route::get('/', [EstadisticasAccesos::class, 'mostrarReporte'])
        ->name('rep_metro.reporte');

    Route::post('/filtros', [EstadisticasAccesos::class, 'aplicarFiltros'])
        ->name('rep_metro.filtros');

    Route::post('/exportar', [EstadisticasAccesos::class, 'exportarCSV'])
        ->name('rep_metro.exportar');
});

// definimos las rutas que requieren autenticacion y un rol de superadmin o admin
// Route::middleware(['auth', 'rol:qr_admin-SuperAdmin'])->group(function () {});
