<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\User;
use App\Models\Giros;

class ReportesPdfController extends Controller
{
    //Reportes Usuarios
    public function usuariosPrint(int $sucursal_id)
    {
        if($sucursal_id == 0){
            $users = User::orderBy('sucursal_id')
                ->orderBy('name')
                ->get();
            $titulo = "Reporte de Usuarios - Todas las Sucursales";
        }else{
            $users = User::where('sucursal_id', $sucursal_id)
                ->orderBy('name')
                ->get();
            $titulo = "Reporte de Usuarios - Sucursal " . optional($users->first()->sucursal)->nombre;
        }
        $pdf = Pdf::loadView('usuariosPrint', ['users' => $users, "titulo" => $titulo]);
        return $pdf->stream('usuarios.pdf');
    }

    public function giroReciboPrint(int $id)
    {
        $giro = Giros::findOrFail($id);
        $pdf = Pdf::loadView('giroReciboPrint', ['giro' => $giro]);
        return $pdf->stream('giro_recibo_' . $giro->id . '.pdf');
    }
}
