<table>
    <thead>
        <tr>
            <th>Sucursal</th>
            <th>Almacén</th>
            <th>Artículo</th>
            <th>Descripción</th>
            <th>Existencias</th>
            <th>Reservado</th>
            <th>Remisionado</th>
            <th>Disponible</th>
            <th>Apartado</th>
        </tr>
    </thead>
    <tbody>
        @foreach($inventarios as $item)
            <tr>
                <td>{{ floor($item->almacen) }}</td>
                <td>{{ ltrim($item->almacen,'0') }}</td>
                <td>{{ $item->articulo }}</td>
                <td>{{ optional($item->producto)->descripcion }}</td>
                <td>{{ $item->existencias }}</td>
                <td>{{ $item->reservado }}</td>
                <td>{{ $item->remisionado }}</td>
                <td>{{ $item->disponible }}</td>
                <td>{{ $item->apartado }}</td>
            </tr>
        @endforeach
    </tbody>
</table>