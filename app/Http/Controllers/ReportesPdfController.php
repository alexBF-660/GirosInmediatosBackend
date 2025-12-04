<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Giros;
use App\Models\Paises;
use App\Models\Estado_Giros;
use App\Models\Departamento;
use App\Models\Sucursales;
use App\Models\MovimientoCapital;
use App\Models\MovimientosAdministrativos;

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

    //reportes Giros
    public function giroReciboPrint(int $id)
    {
        $giro = Giros::findOrFail($id);
        $pdf = Pdf::loadView('giroReciboPrint', ['giro' => $giro]);
        return $pdf->stream('giro_recibo_' . $giro->id . '.pdf');
    }

    public function giroReporteDiaPrint(int $sucursal_id, String $fecha){
        
        if($sucursal_id != 0){
            $girosEnviados = Giros::where('sucursal_origen_id', $sucursal_id)
            ->whereDate('fecha_envio', $fecha)
            ->get();

            $girosRecibidos = Giros::where('sucursal_destino_id', $sucursal_id)
            ->whereDate('fecha_envio', $fecha)
            ->get();

            $titulo = "Reporte de Diario de Giros - ". Sucursales::find($sucursal_id)->nombre;

            $movimientoCapital = MovimientoCapital::where('sucursal_id', $sucursal_id)
            ->whereDate('created_at', $fecha)
            ->get();
        }else{
            $girosEnviados = Giros::whereDate('fecha_envio', $fecha)
            ->get();

            $girosRecibidos = Giros::whereDate('fecha_envio', $fecha)
            ->get();

            $titulo = "Reporte de Diario de Giros - Todas las Sucursales";

            $movimientoCapital = MovimientoCapital::whereDate('fecha', $fecha)
            ->get();
        }

        $totalEnviado = $girosEnviados->sum('monto_enviado');
        $totalRecibido = $girosRecibidos->sum('monto_enviado');

        $pdf = Pdf::loadView('girosDiarioPrint', 
        [
            'girosEnviados' => $girosEnviados,
            'girosRecibidos' => $girosRecibidos,
            'fecha' => $fecha,
            "titulo" => $titulo,
            "totalEnviado" => $totalEnviado,
            "totalRecibido" => $totalRecibido,
            "movimientoCapital" => $movimientoCapital
        ]);
        return $pdf->stream('giros_diario_.pdf');
    }

    public function giroReporteRangoPrint(int $sucursal_id, string $fecha_inicio, string $fecha_fin)
    {
        $inicio = Carbon::parse($fecha_inicio)->startOfDay();
        $fin = Carbon::parse($fecha_fin)->endOfDay();

        if ($inicio->gt($fin)) {
            [$inicio, $fin] = [$fin, $inicio];
        }

        if($sucursal_id != 0){
            $girosEnviados = Giros::where('sucursal_origen_id', $sucursal_id)
            ->whereBetween('fecha_envio', [$inicio, $fin])
            ->get();

            $girosRecibidos = Giros::where('sucursal_destino_id', $sucursal_id)
            ->whereBetween('fecha_envio', [$inicio, $fin])
            ->get();

            $titulo = "Reporte de Giros - ". Sucursales::find($sucursal_id)->nombre . " - Rango: " . $fecha_inicio . " a " . $fecha_fin;

            $movimientoCapital = MovimientoCapital::where('sucursal_id', $sucursal_id)
            ->whereBetween('fecha', [$inicio, $fin])
            ->get();
        }else{
            $girosEnviados = Giros::whereBetween('fecha_envio', [$inicio, $fin])->get();

            $girosRecibidos = Giros::whereBetween('fecha_envio', [$inicio, $fin])->get();

            $titulo = "Reporte  de Giros - Todas las Sucursales - Rango: " . $fecha_inicio . " a " . $fecha_fin;

            $movimientoCapital = MovimientoCapital::whereBetween('fecha', [$inicio, $fin])->get();
        }
        $totalEnviado = $girosEnviados->sum('monto_enviado');
        $totalRecibido = $girosRecibidos->sum('monto_enviado');

        $pdf = Pdf::loadView('girosRangoPrint',
        [
            'girosEnviados' => $girosEnviados,
            'girosRecibidos' => $girosRecibidos,
            "titulo" => $titulo,
            "totalEnviado" => $totalEnviado,
            "totalRecibido" => $totalRecibido,
            "movimientoCapital" => $movimientoCapital
        ]);

        return $pdf->stream('giros_rango.pdf');
    }

    //paises reportes
    public function paisesPrint()
    {
        $paises = Paises::orderBy('nombre')->get();
        $titulo = "Reporte de Paises";
        $pdf = Pdf::loadView('paisesPrint', ["paises" => $paises, "titulo" => $titulo]);
        return $pdf->stream('paises.pdf');
    }

    //estados de giros reportes
    public function estadoGirosPrint()
    {
        $estadoGiros = Estado_Giros::orderBy('nombre')->get();
        $titulo = "Reporte de Estados de Giros";
        $pdf = Pdf::loadView('estadoGirosPrint', ["estadoGiros" => $estadoGiros, "titulo" => $titulo]);
        return $pdf->stream('paises.pdf');
    }

    //departamentos reportes
    public function departamentosPrint(){
        $departamentos = \App\Models\Departamento::orderBy('nombre')->get();
        $titulo = "Reporte de Departamentos";
        $pdf = Pdf::loadView('departamentoPrint', ["departamentos" => $departamentos, "titulo" => $titulo]);
        return $pdf->stream('departamento.pdf');
    }

    //sucursales reportes
    public function sucursalesPrint(){
        $sucursales = Sucursales::orderBy('departamento_id')->get();
        $titulo = "Reporte de Sucursales";
        $pdf = Pdf::loadView('sucursalesPrint', ["sucursales" => $sucursales, "titulo" => $titulo]);
        return $pdf->stream('sucursales.pdf');
    }

    public function movimientosAdmPrint(){
        $movimientos = MovimientosAdministrativos::orderBy('fecha', 'desc')->get();
        $titulo = "Reporte de Movimientos Administrativos";
        $pdf = Pdf::loadView('movimientosAdmPrint', ["movimientos" => $movimientos, "titulo" => $titulo]);
        return $pdf->stream('movimientos_administrativos.pdf');
    }

    public function movimientoCapitalPrint(int $sucursal_id, String $fecha){
        $movimientos = MovimientoCapital::where('sucursal_id', $sucursal_id)
        ->whereDate('fecha', $fecha)
        ->orderBy('fecha', 'desc')->get();
        
        $titulo = "Reporte de Movimiento de Capital";
        $pdf = Pdf::loadView('movimientoCapitalPrint', ["movimientos" => $movimientos, "titulo" => $titulo]);
        return $pdf->stream('movimiento_capital.pdf');
    }
}
