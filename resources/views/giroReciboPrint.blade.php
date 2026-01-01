<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recibo de Giro #{{ $giro->id }}</title>

    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #1f2937;
            margin: 0;
            padding: 0;
        }

        .container {
            padding: 24px;
        }

        /* ====== HEADER ====== */
        .header {
            width: 100%;
            margin-bottom: 20px;
            padding: 16px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .logo {
            width: 90px;
        }

        .title {
            text-align: center;
        }

        .title h1 {
            font-size: 20px;
            margin: 0;
            color: #0A0F2C;
        }

        .title p {
            margin: 4px 0;
            font-size: 12px;
            color: #6b7280;
        }

        .badge {
            display: inline-block;
            padding: 4px 10px;
            font-size: 11px;
            border-radius: 12px;
            background-color: #0A0F2C;
            color: #b8860b;
            margin-top: 6px;
        }

        /* ====== INFO BLOCK ====== */
        .block {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 14px;
            margin-bottom: 20px;
        }

        .block-title {
            font-size: 13px;
            font-weight: bold;
            color: #0A0F2C;
            margin-bottom: 10px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 6px 4px;
            vertical-align: top;
        }

        .label {
            font-weight: bold;
            color: #374151;
            width: 20%;
        }

        /* ====== DETAIL TABLE ====== */
        .detail-table {
            width: 100%;
            border-collapse: collapse;
        }

        .detail-table th {
            background-color: #0A0F2C;
            color: #ffffff;
            padding: 10px;
            font-size: 12px;
            text-align: left;
        }

        .detail-table td {
            border-bottom: 1px solid #e5e7eb;
            padding: 10px;
        }

        .detail-table tfoot td {
            font-weight: bold;
            background-color: #f9fafb;
        }

        .right {
            text-align: right;
        }

        /* ====== TOTAL BOX ====== */
        .total-box {
            width: 100%;
            margin-top: 10px;
        }

        .total-box td {
            padding: 6px 0;
        }

        /* ====== FOOTER ====== */
        .footer {
            text-align: center;
            font-size: 11px;
            color: #6b7280;
            margin-top: 30px;
        }
    </style>
</head>

<body>
<div class="container">

    <!-- HEADER -->
    <div class="header">
        <table class="header-table">
            <tr>
                <td class="logo">
                    <img src="{{ public_path('storage/logo_giros_inmediatos.png') }}"
                         style="width: 80px;">
                </td>
                <td class="title">
                    <h1>GIROS INMEDIATOS</h1>
                    <p>Agencia de giros monetarios</p>
                    <div class="badge">
                        Recibo N° {{ $giro->id }}
                    </div>
                    <p>Fecha de emisión: {{ date('d/m/Y') }}</p>
                </td>
            </tr>
        </table>
    </div>

    <!-- DATOS DEL GIRO -->
    <div class="block">
        <div class="block-title">Información del Giro</div>

        <table class="info-table">
            <tr>
                <td class="label">Remitente:</td>
                <td>{{ $giro->nombre_remitente }}</td>

                <td class="label">Sucursal Origen:</td>
                <td>{{ $giro->sucursalOrigen->nombre }}</td>
            </tr>
            <tr>
                <td class="label">Consignatario:</td>
                <td>{{ $giro->nombre_consignatario }}</td>

                <td class="label">Sucursal Destino:</td>
                <td>{{ $giro->sucursalDestino->nombre }}</td>
            </tr>
            <tr>
                <td class="label">CI Consignatario:</td>
                <td>{{ $giro->ci_consignatario ?? 'N/A' }}</td>

                <td class="label">Estado:</td>
                <td>{{ $giro->estado->nombre }}</td>
            </tr>
        </table>
    </div>

    <!-- DETALLE ECONÓMICO -->
    <div class="block">
        <div class="block-title">Detalle Económico</div>

        <table class="detail-table">
            <thead>
                <tr>
                    <th>Concepto</th>
                    <th class="right">Monto (Bs.)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Monto enviado</td>
                    <td class="right">{{ number_format($giro->monto_enviado, 2) }}</td>
                </tr>
                <tr>
                    <td>Comisión de envío</td>
                    <td class="right">{{ number_format($giro->comision_envio, 2) }}</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td>TOTAL</td>
                    <td class="right">
                        {{ number_format($giro->monto_enviado + $giro->comision_envio, 2) }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- FECHAS -->
    <div class="block">
        <div class="block-title">Fechas</div>

        <table class="total-box">
            <tr>
                <td class="label">Fecha de envío:</td>
                <td>{{ $giro->fecha_envio->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td class="label">Fecha de entrega:</td>
                <td>
                    {{ $giro->fecha_entrega
                        ? $giro->fecha_entrega->format('d/m/Y')
                        : 'Pendiente de entrega' }}
                </td>
            </tr>
        </table>
    </div>

    <!-- FOOTER -->
    <div class="footer">
        Gracias por confiar en <strong>Giros Inmediatos</strong><br>
        Este documento es válido como comprobante de envío
    </div>

</div>
</body>
</html>
