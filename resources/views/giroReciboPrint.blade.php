<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recibo de Giro #{{ $giro->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            padding: 20px;
        }

        h1, h2, h3 {
            margin: 0;
        }

        .header, .footer {
            text-align: center;
            margin-bottom: 20px;
        }

        .info, .detalle {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .info td {
            padding: 5px;
        }

        .detalle th, .detalle td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        .detalle th {
            background-color: #f0f0f0;
        }

        .totales {
            margin-top: 10px;
            width: 100%;
            margin-bottom: 20px;
        }

        .totales td {
            padding: 5px;
        }

        .right {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Recibo de Giro</h1>
            <h3>Giro N°: {{ $giro->id }}</h3>
        </div>

        <table class="info">
            <tr>
                <td class="bold">Remitente:</td>
                <td>{{ $giro->nombre_remitente }}</td>
                <td class="bold">Sucursal Origen:</td>
                <td>{{ $giro->sucursalOrigen->nombre }}</td>
            </tr>
            <tr>
                <td class="bold">Consignatario:</td>
                <td>{{ $giro->nombre_consignatario }}</td>
                <td class="bold">Sucursal Destino:</td>
                <td>{{ $giro->sucursalDestino->nombre }}</td>
            </tr>
            <tr>
                <td class="bold">CI Consignatario:</td>
                <td>{{ $giro->ci_consignatario ?? 'N/A' }}</td>
                <td class="bold">Estado:</td>
                <td>{{ $giro->estado->nombre }}</td>
            </tr>
        </table>

        <table class="detalle">
            <thead>
                <tr>
                    <th>Concepto</th>
                    <th>Monto</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Monto Enviado</td>
                    <td>{{ number_format($giro->monto_enviado, 2) }}</td>
                </tr>
                <tr>
                    <td>Comisión de Envío</td>
                    <td>{{ number_format($giro->comision_envio, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <table class="totales">
            <tr>
                <td class="bold">Fecha de Envío:</td>
                <td>{{ $giro->fecha_envio->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td class="bold">Fecha de Entrega:</td>
                <td>{{ $giro->fecha_entrega ? $giro->fecha_entrega->format('d/m/Y') : 'Sin fecha de entrega' }}</td>
            </tr>
        </table>

        <div class="footer">
            <p>Gracias por su preferencia</p>
            <p style="text-decoration: underline;"> Giros Inmediatos</p>
        </div>
    </div>
</body>
</html>
