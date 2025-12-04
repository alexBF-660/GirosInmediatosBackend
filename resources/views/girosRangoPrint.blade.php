<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Giros diario</title>

<style>
    /* Fuente general */
    body {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
    }

    h1 {
        text-align: center;
        font-size: 16px;
        margin-bottom: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }

    table th, table td {
        border: 1px solid #000;
        padding: 6px;
        font-size: 11px;
    }

    table th {
        background-color: #0A0F2C;
        color: #b8860b;
        font-weight: bold;
        text-align: left;
    }

    .text-center {
        text-align: center;
    }
</style>
</head>

<body>

    <!-- CABECERA -->
    <table style="width: 100%; margin-bottom: 20px; border: 2px solid #fff;">
        <tr>
            <td style="width: 80px; border: 2px solid #fff;">
                
                <!-- OPCIÓN 1: DOMPDF (Base64) — RECOMENDADO -->
                <!--
                <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('storage/logo_giros_inmediatos.png'))) }}"
                     alt="Logo" style="width: 80px;">
                -->

                <!-- OPCIÓN 2: Ruta normal (solo funciona en el navegador, NO en DOMPDF) -->
                <img src="{{ public_path('storage/logo_giros_inmediatos.png') }}" 
                     alt="Logo" 
                     style="width: 80px;">

            </td>

            <td style="text-align: center;">
                <h2 style="margin: 0; padding: 0; font-size: 20px; color: #b8860b;">
                    GIROS INMEDIATOS
                </h2>

                <p style="margin: 0; font-size: 12px; color: #444;">
                    Agencia de giros monetarios
                </p>

                <h3 style="margin: 0; color: #444; font-size: 15px;">
                    {{ $titulo }}
                </h3>

                <span style="font-size: 11px; color: #555;">
                    Fecha: {{ date('d/m/Y') }}
                </span>
            </td>
        </tr>
    </table>

    <!-- GIROS ENVIADOS -->
    <p><b>Giros Enviados</b></p>

    <table>
        <thead>
            <tr>
                <th>N° Guia</th>
                <th>Remitente</th>
                <th>Consignatario</th>
                <th>Sucursal origen</th>
                <th>Sucursal destino</th>
                <th>Monto</th>
                <th>Comision</th>
                <th>Estado</th>
                <th>Fecha envio</th>
            </tr>
        </thead>

        <tbody>
            @if(count($girosEnviados) > 0)
                @foreach ($girosEnviados as $giro)
                <tr>
                    <td class="text-center">{{ $giro->id }}</td>
                    <td>{{ $giro->nombre_remitente }}</td>
                    <td>{{ $giro->nombre_consignatario }}</td>
                    <td>{{ $giro->sucursalOrigen->nombre }}</td>
                    <td>{{ $giro->sucursalDestino->nombre }}</td>
                    <td>{{ number_format($giro->monto_enviado, 2) }}</td>
                    <td>{{ number_format($giro->comision_envio, 2) }}</td>
                    <td>{{ $giro->estado->nombre }}</td>
                    <td>{{ $giro->fecha_envio->format('d/m/Y') }}</td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="9" class="text-center">No hay giros enviados para esta fecha.</td>
                </tr>
            @endif
        </tbody>

        <tfoot>
            <tr>
                <th colspan="9" class="text-center">
                    Monto Total Enviado: {{ $totalEnviado }} Bs.-
                </th>
            </tr>
        </tfoot>
    </table>

    <!-- GIROS RECIBIDOS -->
    <p><b>Giros Recibidos</b></p>

    <table>
        <thead>
            <tr>
                <th>N° Guia</th>
                <th>Remitente</th>
                <th>Consignatario</th>
                <th>Sucursal origen</th>
                <th>Sucursal destino</th>
                <th>Monto</th>
                <th>Comision</th>
                <th>Estado</th>
                <th>Fecha envio</th>
            </tr>
        </thead>

        <tbody>
            @if(count($girosRecibidos) > 0)
                @foreach ($girosRecibidos as $giro)
                <tr>
                    <td class="text-center">{{ $giro->id }}</td>
                    <td>{{ $giro->nombre_remitente }}</td>
                    <td>{{ $giro->nombre_consignatario }}</td>
                    <td>{{ $giro->sucursalOrigen->nombre }}</td>
                    <td>{{ $giro->sucursalDestino->nombre }}</td>
                    <td>{{ number_format($giro->monto_enviado, 2) }}</td>
                    <td>{{ number_format($giro->comision_envio, 2) }}</td>
                    <td>{{ $giro->estado->nombre }}</td>
                    <td>{{ $giro->fecha_envio->format('d/m/Y') }}</td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="9" class="text-center">No hay giros recibidos para esta fecha.</td>
                </tr>
            @endif
        </tbody>
        <tfoot>
            <tr>
                <th colspan="9" class="text-center">
                    Monto Total Recibido: {{ $totalRecibido }} Bs.-
                </th>
            </tr>
        </tfoot>
    </table>

    <!-- SECCIÓN DE DETALLES -->
<p><b>Detalles por sucursal</b></p>

@php
    // Agrupar ENVIADOS por sucursal destino
    $detalleEnviados = $girosEnviados->groupBy('sucursalDestino.nombre')
        ->map(function($items){
            return [
                'cantidad' => $items->count(),
                'monto'    => $items->sum('monto_enviado')
            ];
        });

    // Agrupar RECIBIDOS por sucursal origen
    $detalleRecibidos = $girosRecibidos->groupBy('sucursalOrigen.nombre')
        ->map(function($items){
            return [
                'cantidad' => $items->count(),
                'monto'    => $items->sum('monto_enviado')
            ];
        });
@endphp

<table>
    <thead>
        <tr>
            <th>Sucursal</th>
            <th>Giros Enviados</th>
            <th>Monto Enviado</th>
            <th>Giros Recibidos</th>
            <th>Monto Recibido</th>
        </tr>
    </thead>

    <tbody>
        @php
            // Obtener lista unificada de todas las sucursales que enviaron o recibieron
            $todasSucursales = collect(array_unique(
                array_merge(
                    $detalleEnviados->keys()->toArray(),
                    $detalleRecibidos->keys()->toArray()
                )
            ));
        @endphp

        @foreach ($todasSucursales as $sucursal)
        <tr>
            <td><b>{{ $sucursal }}</b></td>

            <td class="text-center">
                {{ $detalleEnviados[$sucursal]['cantidad'] ?? 0 }}
            </td>

            <td class="text-center">
                {{ number_format($detalleEnviados[$sucursal]['monto'] ?? 0, 2) }} Bs.-
            </td>

            <td class="text-center">
                {{ $detalleRecibidos[$sucursal]['cantidad'] ?? 0 }}
            </td>

            <td class="text-center">
                {{ number_format($detalleRecibidos[$sucursal]['monto'] ?? 0, 2) }} Bs.- 
            </td>
        </tr>
        @endforeach
    </tbody>

    <tfoot>
        <tr>
            <th>Total general</th>
            <th class="text-center">{{ $girosEnviados->count() }}</th>
            <th class="text-center">{{ number_format($girosEnviados->sum('monto_enviado'), 2) }} Bs.-</th>
            <th class="text-center">{{ $girosRecibidos->count() }}</th>
            <th class="text-center">{{ number_format($girosRecibidos->sum('monto_enviado'), 2) }} Bs.-</th>
        </tr>
    </tfoot>
</table>

<p><b>Movimiento del capital</b></p>

<table>
        <thead>
            <tr>
                <th>N°</th>
                <th>Fecha</th>
                <th>Sucursal</th>
                <th>Total enviado</th>
                <th>Total recibido</th>
                <th>balance del dia</th>
                <th>Capital inicial</th>
                <th>Capital final</th>
            </tr>
        </thead>

        <tbody>
            @if(count($movimientoCapital) > 0)
                @foreach ($movimientoCapital as $index => $movimiento)
                <tr>
                    <td class="text-center">{{ $index+1 }}</td>
                    <td>{{ $movimiento->fecha }}</td>
                    <td>{{ $movimiento->sucursal->nombre }}</td>
                    <td>{{ $movimiento->total_enviado }}</td>
                    <td>{{ $movimiento->total_recibido }}</td>
                    <td>{{ $movimiento->balance_dia }}</td>
                    <td>{{ $movimiento->capital_inicial }}</td>
                    <td>{{ $movimiento->capital_actual }}</td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="8" class="text-center">No hay giros enviados para esta fecha.</td>
                </tr>
            @endif
        </tbody>
    </table>

</body>
</html>
