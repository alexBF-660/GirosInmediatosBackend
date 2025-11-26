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