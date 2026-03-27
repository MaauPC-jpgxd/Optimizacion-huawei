<table>
    {{-- 🔷 ENCABEZADO CORPORATIVO --}}
    <tr>
        <td colspan="5"
            style="text-align:center;
                   font-weight:bold;
                   font-size:16px;
                   background-color:#0d6efd;
                   color:white;
                   height:30px;">
            REPORTE DE INVENTARIO HUAWEI
        </td>
    </tr>

    <tr>
        <td colspan="5"
            style="text-align:center;
                   font-size:11px;
                   color:#555;
                   height:20px;">
            Fecha de generación: {{ now()->format('d/m/Y H:i') }}
        </td>
    </tr>

    <tr></tr>

    {{-- 🔥 HEADERS --}}
    <thead>
        <tr>
            <th style="background-color:#212529; color:white; font-weight:bold;">Sucursal</th>
            <th style="background-color:#212529; color:white; font-weight:bold;">Almacén</th>
            <th style="background-color:#212529; color:white; font-weight:bold;">Artículo</th>
            <th style="background-color:#212529; color:white; font-weight:bold;">Descripción</th>
            <th style="background-color:#212529; color:white; font-weight:bold;">Disponible</th>
        </tr>
    </thead>

    <tbody>
        @foreach($inventarios as $item)
        <tr>
            <td>{{ floor($item->almacen) }}</td>

            <td>{{ $item->almacen }}</td>

            <td>{{ $item->articulo }}</td>

            {{-- 🔥 NUNCA VACÍO --}}
            <td>
                {{ optional($item->producto)->descripcion ?? $item->articulo }}
            </td>

            {{-- 🔥 RESALTADO STOCK --}}
            <td style="
                font-weight:bold;
                color: {{ $item->disponible <= 5 ? 'red' : 'green' }};
            ">
                {{ number_format($item->disponible) }}
            </td>
        </tr>
        @endforeach
    </tbody>

    {{-- 🔥 TOTALES --}}
    <tfoot>
        <tr>
            <td colspan="4"
                style="text-align:right; font-weight:bold; background:#f1f1f1;">
                TOTAL DISPONIBLE:
            </td>

            <td style="font-weight:bold; background:#f1f1f1;">
                {{ number_format($inventarios->sum('disponible')) }}
            </td>
        </tr>
    </tfoot>
</table>