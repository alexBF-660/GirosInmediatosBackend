<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Paises</title>

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
                    <h1 style="margin: 0; color: #444;">{{$titulo}}</h1>
                    <span style="font-size: 11px; color: #555;">
                    Fecha: {{ date('d/m/Y') }}
                </span>
                </p>
            </td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>N°</th>
                <th>Fecha</th>
                <th>Tipo</th>
                <th>Sucursal</th>
                <th>Monto</th>
                <th>Descripción</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($movimientos as $index => $movimiento)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $movimiento->fecha }}</td>
                    <td>{{ $movimiento->tipo == 1 ? "Ingreso" : "Retiro"}}</td>
                    <td>{{ $movimiento->sucursal->nombre }}</td>
                    <td>{{ $movimiento->monto }} Bs.-</td>
                    <td>{{ $movimiento->descripcion }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="6" class="text-center">Total de movimientos registrados: {{ count($movimientos) }}</th>
            </tr>
    </table>

</body>
</html>
