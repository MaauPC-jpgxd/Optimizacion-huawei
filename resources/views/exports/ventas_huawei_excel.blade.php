<table>
    <thead>
        <tr>
            <
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
                <td>{{ $venta->cantidad }}</td>
                <td>{{ $venta->precio }}</td>
                <td>{{ $venta->descuento }}</td>
                <td>{{ $venta->monto }}</td>
                <td>{{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y') }}</td>
            </tr>

        @endforeach

        <tr>
            <td colspan="4"><strong>TOTALES</strong></td>
            <td><strong>{{ $totalCantidad }}</strong></td>
            <td></td>
            <td></td>
            <td><strong>{{ $totalMonto }}</strong></td>
            <td></td>
        </tr>

    </tbody>
</table>