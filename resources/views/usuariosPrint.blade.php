<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Usuarios</title>

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
            background-color: #f1f1f1;
            font-weight: bold;
            text-align: left;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>

<body>

    <h1>{{ $titulo ?? '' }}</h1>

    <table>
        <thead>
            <tr>
                <th>N°</th>
                <th>Nombre completo</th>
                <th>Correo electronico</th>
                <th>Número de carnet</th>
                <th>Celular</th>
                <th>Genero</th>
                <th>Sucursal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $index => $user)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $user->name }} {{ $user->ap_paterno }} {{ $user->ap_materno }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->ci }}</td>
                    <td>{{ $user->celular }}</td>
                    <td>{{ $user->genero }}</td>
                    <<td>{{ $user->sucursal->nombre }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
