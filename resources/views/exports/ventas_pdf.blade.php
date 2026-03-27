<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ventas Factura</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .logo {
            width: 130px;
            margin-bottom: 5px;
        }

        h2 {
            margin: 0;
            font-size: 16px;
            color: #0d6efd;
        }

        .info {
            font-size: 9px;
            margin-top: 3px;
        }

        .line {
            border-top: 2px solid #0d6efd;
            margin: 10px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background-color: #0d6efd;
            color: white;
            padding: 6px;
            font-size: 9px;
        }

        td {
            padding: 5px;
            font-size: 9px;
            text-align: center;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .money {
            text-align: right;
            padding-right: 8px;
        }

        .footer {
            margin-top: 10px;
            font-size: 10px;
        }

        .totales {
            margin-top: 10px;
            width: 40%;
            float: right;
            border-collapse: collapse;
        }

        .totales td {
            border: 1px solid #ccc;
            padding: 6px;
            font-size: 10px;
        }

        .totales .label {
            background-color: #f5f5f5;
            font-weight: bold;
            text-align: left;
        }

        .totales .value {
            text-align: right;
        }
    </style>
</head>

<body>

    <div class="header">
        <img src="{{ public_path('img/Recurso 18@3x.png') }}" class="logo">

        <h2>Reporte de Ventas Factura</h2>

        <div class="info">
            Fecha de generación: {{ now()->format('d/m/Y') }} <br>
            Total registros: {{ count($ventas) }}
        </div>
    </div>

    <div class="line"></div>

    <table>
        <thead>
            <tr>
                <th>Folio</th>
                <th>Tipo</th>
                <th>Sucursal</th>
                <th>Almacén</th>
                <th>Fecha</th>
                <th>Importe</th>
                <th>Total</th>
            </tr>
        </thead>

        <tbody>
            @php
                $sumaImporte = 0;
                $sumaTotal = 0;
            @endphp

            @forelse($ventas as $v)

                @php
                    $sumaImporte += $v->Importe ?? 0;
                    $sumaTotal += $v->Total ?? 0;
                @endphp

                <tr>
                    <td>{{ $v->MovID }}</td>
                    <td>{{ $v->Mov }}</td>
                    <td>{{ $v->Sucursal }}</td>
                    <td>{{ $v->Almacen }}</td>

                    <td>
                        {{ $v->FechaEmision 
                            ? \Carbon\Carbon::parse($v->FechaEmision)->format('d/m/Y') 
                            : '-' }}
                    </td>

                    <td class="money">
                        ${{ number_format($v->Importe ?? 0, 2) }}
                    </td>

                    <td class="money">
                        <strong>${{ number_format($v->Total ?? 0, 2) }}</strong>
                    </td>
                </tr>

            @empty
                <tr>
                    <td colspan="7">Sin registros</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- 🔥 TOTALES --}}
    <table class="totales">
        <tr>
            <td class="label">Total Importe</td>
            <td class="value">${{ number_format($sumaImporte, 2) }}</td>
        </tr>
        <tr>
            <td class="label">Total General</td>
            <td class="value"><strong>${{ number_format($sumaTotal, 2) }}</strong></td>
        </tr>
    </table>

</body>
</html>