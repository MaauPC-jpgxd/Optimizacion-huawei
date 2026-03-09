<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ventas Huawei</title>

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
            /* background-color: #198754; /* Verde ventas */
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

        .total-row {
            background-color: #f2f2f2;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="header">
        <img src="{{ public_path('img/Recurso 18@3x.png') }}" class="logo">
        <h2>Reporte de Ventas Tiendas Huawei</h2>
        
        <p>Fecha de generación: {{ now()->format('d/m/Y ') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                
                <th>Sucursal</th>
                <th>Artículo</th>
                <th>Descripción</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Descuento</th>
                <th>Monto</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalCantidad = 0;
                $totalMonto = 0;
            @endphp

            @foreach($ventas as $venta)

                @php
                    $totalCantidad += $venta->cantidad;
                    $totalMonto += $venta->monto;
                @endphp

                <tr>
                    
                    <td>{{ $venta->sucursal }}</td>
                    <td>{{ $venta->articulo }}</td>
                    <td>{{ optional($venta->producto)->descripcion }}</td>
                    <td>{{ number_format($venta->cantidad, 0) }}</td>
                    <td>${{ number_format($venta->precio, 2) }}</td>
                    <td>${{ number_format($venta->descuento, 2) }}</td>
                    <td>${{ number_format($venta->monto, 2) }}</td>
                    <td>{{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y') }}</td>
                </tr>

            @endforeach

            <tr class="total-row">
                <td colspan="4">TOTALES</td>
                <td>{{ number_format($totalCantidad, 0) }}</td>
                <td></td>
                <td></td>
                <td>${{ number_format($totalMonto, 2) }}</td>
                <td></td>
            </tr>

        </tbody>
    </table>

</body>
</html>