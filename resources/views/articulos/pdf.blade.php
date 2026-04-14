<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Inventario</title>

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

        .small { width: 60px; }
        .medium { width: 80px; }
        .wide { width: 100px; }

        .totales {
            font-weight: bold;
            background-color: #f0f0f0;
        }
    </style>
</head>

<body>

    <div class="header">
        <img src="{{ public_path('img/Recurso 18@3x.png') }}" class="logo">
        <h2>Reporte de Inventario</h2>
        <p>Fecha: {{ now()->format('d/m/Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="medium">Artículo</th>
                <th class="desc">Descripción</th>
                <th class="medium">Categoría</th>
                <th class="medium">Fabricante</th>
                <th class="small">Alm</th>
                <th class="small">Exist</th>
                <th class="small">Disp</th>
                <th class="small">Estatus</th>
                <th class="medium">Costo Prom</th>
                <th class="medium">Valor</th>
            </tr>
        </thead>

        <tbody>
            @php
                $totalExistencias = 0;
                $totalDisponible = 0;
                $totalValor = 0;
            @endphp

            @foreach($articulos as $a)
            @php
                $valor = ($a->existencias ?? 0) * ($a->CostoPromedio ?? 0);
                $totalExistencias += $a->existencias ?? 0;
                $totalDisponible += $a->disponible ?? 0;
                $totalValor += $valor;
            @endphp
            <tr>
                <td>{{ $a->Articulo }}</td>
                <td class="desc">{{ $a->DescripcionCompleta  }}</td>
                <td>{{ $a->Categoria }}</td>
                <td>{{ $a->Fabricante }}</td>
                <td>{{ $a->almacen }}</td>
                <td>{{ $a->existencias ?? 0 }}</td>
                <td>{{ $a->disponible ?? 0 }}</td>
                <td>{{ $a->Estatus }}</td>
                <td>${{ number_format($a->CostoPromedio ?? 0, 2) }}</td>
                <td>${{ number_format($valor, 2) }}</td>
            </tr>
            @endforeach

            {{-- 🔹 FILA DE TOTALES --}}
            <tr class="totales">
                <td colspan="5">Totales</td>
                <td>{{ number_format($totalExistencias) }}</td>
                <td>{{ number_format($totalDisponible) }}</td>
                <td></td>
                <td></td>
                <td>${{ number_format($totalValor, 2) }}</td>
            </tr>
        </tbody>
    </table>

</body>
</html>