<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Inventario General</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo {
            width: 180px;
            margin-bottom: 10px;
        }

        h2 {
            margin: 5px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background-color: #0d6efd;
            color: white;
            padding: 6px;
            font-size: 11px;
        }

        td {
            padding: 5px;
            text-align: center;
            font-size: 10px;
        }

        table, th, td {
            border: 1px solid #ccc;
        }
    </style>
</head>
<body>

    <div class="header">
        <img src="{{ public_path('img/Recurso 18@3x.png') }}" class="logo">
        <h2>Inventario General por Sucursal</h2>
        <p>Fecha: {{ now()->format('d/m/Y ') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Sucursal</th>
                <th>Artículo</th>
                <th>Descripción</th>
                <th>Existencias</th>
            </tr>
        </thead>
        <tbody>
            @foreach($inventarios as $item)
                <tr>
                    <td>{{ floor($item->almacen) }}</td>
                    <td>{{ $item->articulo }}</td>
                    <td>{{ optional($item->producto)->descripcion }}</td>
                    <td>{{ $item->existencias }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>