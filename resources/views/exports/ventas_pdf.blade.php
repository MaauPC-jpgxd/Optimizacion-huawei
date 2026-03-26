<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ventas Factura</title>

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

        .desc {
            text-align: left;
            word-wrap: break-word;
            width: 120px;
        }

        .small { width: 55px; }
        .medium { width: 80px; }
        .wide { width: 100px; }
    </style>
</head>

<body>

    <div class="header">
        {{-- ⚠️ IMPORTANTE: valida que exista la imagen --}}
        <img src="{{ public_path('img/Recurso 18@3x.png') }}" class="logo">

        <h2>Reporte de Ventas Factura</h2>

        <p>Fecha: {{ now()->format('d/m/Y') }}</p>

        {{-- 🔥 EXTRA PRO: total registros --}}
        <p>Total registros: {{ count($ventas) }}</p>
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
            @forelse($ventas as $v)
            <tr>
                <td>{{ $v->Sucursal }}</td>

                <td>{{ $v->Articulo ?? '-' }}</td>

                <td class="desc">
                    {{ $v->ArtDescripcion ?? 'Sin descripción' }}
                </td>

                <td>{{ number_format($v->Cantidad ?? 0, 0) }}</td>

                <td>{{ $v->Cliente ?? '-' }}</td>

                <td>
                    {{ $v->CteNombre ?? 'N/A' }}
                </td>

                <td>{{ $v->MovID }}</td>

                <td>{{ $v->Estatus ?? '-' }}</td>

                <td>{{ $v->Mov }}</td>

                <td>{{ $v->Almacen ?? '-' }}</td>

                <td>
                    {{ $v->FechaEmision 
                        ? \Carbon\Carbon::parse($v->FechaEmision)->format('d/m/Y') 
                        : '-' }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="11">Sin registros</td>
            </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>