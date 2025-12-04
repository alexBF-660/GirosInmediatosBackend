<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportesPdfController;

Route::get('/', function () {
    return view('welcome');
});

//rutas para imprimir reportes de usuarios
Route::get('print/usuariosPrint/{sucursal_id}', [ReportesPdfController::class, 'usuariosPrint'])->name('usuarios.print');

//rutas para imprimir reportes de giros
Route::get('print/giroReciboPrint/{id}', [ReportesPdfController::class, 'giroReciboPrint'])->name('giroRecibo.print');

//rutas para imprimir reportes de giros por dia
Route::get('print/giroReporteDiaPrint/{sucursal_id}&{fecha}', [ReportesPdfController::class, 'giroReporteDiaPrint'])->name('giroDiario.print');

//rutas para imprimir reportes de giros por dia
Route::get('print/giroReporteRangoPrint/{sucursal_id}&{fecha_inicio}&{fecha_fin}', [ReportesPdfController::class, 'giroReporteRangoPrint'])->name('giroRango.print');

//rutas para imprimir reportes de paises
Route::get('print/paisesPrint', [ReportesPdfController::class, 'paisesPrint'])->name('paisesPrint.print');

//rutas para imprimir reportes de estados de giros
Route::get('print/estadoGirosPrint', [ReportesPdfController::class, 'estadoGirosPrint'])->name('estadoGirosPrint.print');

//rutas para imprimir reportes de departamentos
Route::get('print/departamentosPrint', [ReportesPdfController::class, 'departamentosPrint'])->name('departamentosPrint.print');

//rutas para imprimir reportes de sucursales
Route::get('print/sucursalesPrint', [ReportesPdfController::class, 'sucursalesPrint'])->name('sucursalesPrint.print');

//rutas para imprimir reportes de movimientos administrativos
Route::get('print/movimientosAdmPrint', [ReportesPdfController::class, 'movimientosAdmPrint'])->name('movimientosAdmPrint.print');

//rutas para imprimir reportes de movimiento capital
Route::get('print/movimientoCapitalPrint', [ReportesPdfController::class, 'movimientoCapitalPrint'])->name('movimientoCapitalPrint.print');