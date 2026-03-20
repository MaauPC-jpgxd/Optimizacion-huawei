<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ventas Detalle</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
        }

        .logo {
            width: 140px;
            margin-bottom: 5px;
        }

        h2 {
            margin: 2px 0;
        }

        p {
            margin: 2px;
            font-size: 9px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        th {
            background-color: #0d6efd;
            color: white;
            padding: 5px;
            font-size: 9px;
        }

        td {
            padding: 4px;
            font-size: 8px;
            text-align: center;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        /* 🔥 COLUMNAS AJUSTADAS */
        .desc {
            text-align: left;
            word-wrap: break-word;
            width: 120px;
        }

        .small {
            width: 60px;
        }

        .medium {
            width: 80px;
        }

        .wide {
            width: 100px;
        }
    </style>
</head>

<body>

    <div class="header">
        <img src="{{ public_path('img/Recurso 18@3x.png') }}" class="logo">
        <h2>Reporte de Ventas Detalle</h2>
        <p>Fecha: {{ now()->format('d/m/Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="small">Suc</th>
                <th class="medium">Artículo</th>
                <th class="desc">Descripción</th>
                <th class="small">Cant</th>
                <th class="medium">Cliente</th>
                <th class="wide">Nombre</th>
                <th class="medium">Factura</th>
                <th class="small">Estatus</th>
                <th class="small">Tipo</th>
                <th class="small">Alm</th>
                <th class="medium">Fecha</th>
            </tr>
        </thead>

        <tbody>
            @foreach($ventas as $v)
            <tr>
                <td>{{ $v->Sucursal }}</td>
                <td>{{ $v->Articulo }}</td>

                <td class="desc">
                    {{ $v->ArtDescripcion }}
                </td>

                <td>{{ $v->Cantidad }}</td>
                <td>{{ $v->Cliente }}</td>
                <td>{{ $v->CteNombre }}</td>
                <td>{{ $v->MovID }}</td>
                <td>{{ $v->Estatus }}</td>
                <td>{{ $v->Mov }}</td>
                <td>{{ $v->Almacen }}</td>
                <td>{{ \Carbon\Carbon::parse($v->FechaEmision)->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>