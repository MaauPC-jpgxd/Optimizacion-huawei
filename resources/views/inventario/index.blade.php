@extends('adminlte::page')

@section('title', 'Inventario por Sucursal')

@section('content_header')
    <h1 class="fw-bold text-center">Inventario General por Sucursal</h1>
@stop

@section('content')

<div class="card shadow-sm">
    <div class="card-body">
        <form method="GET" action="{{ route('inventario.index') }}">

            <div class="row align-items-end text-center g-3">

                <!-- Sucursal -->
                <div class="col-md-3">
                    <label class="fw-bold d-block">Sucursal</label>
                    <select name="sucursal"
                            class="form-control text-center"
                            onchange="this.form.submit()">
                        <option value="">-- Todas las sucursales --</option>
                        @foreach($sucursales as $sucursal)
                            <option value="{{ $sucursal->clave }}"
                                {{ $sucursalId == $sucursal->clave ? 'selected' : '' }}>
                                Sucursal {{ $sucursal->clave }} - {{ $sucursal->nombre ?? 'Sin nombre' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Buscar -->
                <div class="col-md-3">
                    <label class="fw-bold d-block">Buscar</label>
                    <input type="text"
                           id="buscador"
                           class="form-control text-center"
                           placeholder="Buscar por artículo o descripción...">
                </div>

                <!-- Mostrar + Contador -->
                <div class="col-md-3">
                    <label class="fw-bold d-block">Mostrar</label>
                    <div class="d-flex justify-content-center align-items-center gap-2">
                        <select id="cantidadMostrar"
                                class="form-control form-control-sm text-center"
                                style="width:auto;">
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>

                        <span class="badge bg-primary p-2">
                            Resultados:
                            <span id="contador">{{ $inventarios->count() }}</span>
                        </span>
                    </div>
                </div>

                <!-- Exportar -->
                <div class="col-md-3">
                    <label class="fw-bold d-block">Exportar Inventario Completo</label>
                    <div class="d-flex justify-content-center gap-2">
                        <a href="{{ route('inventario.excel', request()->query()) }}"
                           class="btn btn-success btn-sm">
                            <i class="fas fa-file-excel"></i> Excel
                        </a>

                        <a href="{{ route('inventario.pdf', request()->query()) }}"
                           class="btn btn-danger btn-sm">
                            <i class="fas fa-file-pdf"></i> PDF
                        </a>
                    </div>
                </div>

            </div>

        </form>
    </div>
</div>

<div class="card mt-4 shadow-sm">
    <div class="card-body table-responsive p-0">

        <table class="table table-hover table-striped table-sm align-middle text-nowrap text-center">
            <thead style="background-color:#0d6efd; color:white">
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
                    @if(auth()->user()->role === 'root')
                        <th>Acciones</th>
                    @endif
                </tr>
            </thead>
            <tbody id="tablaInventario">
                @forelse($inventarios as $item)

                    @php
                        $claveSucursal = floor($item->almacen);
                        $sucursalEncontrada = $sucursales->firstWhere('clave', $claveSucursal);
                        $almacenLimpio = ltrim($item->almacen, '0');
                    @endphp

                    <tr>
                        <td>
                            <strong>{{ $claveSucursal }}</strong><br>
                            <small class="text-primary fw-bold">
                                {{ $sucursalEncontrada->nombre ?? 'N/A' }}
                            </small>
                        </td>

                        <td>{{ $almacenLimpio }}</td>

                        <td class="fw-bold">{{ $item->articulo }}</td>

                        <td style="max-width:280px;" class="mx-auto">
                            <small class="text-muted d-block text-truncate"
                                   title="{{ optional($item->producto)->descripcion }}">
                                {{ optional($item->producto)->descripcion ?? 'Sin descripción' }}
                            </small>
                        </td>

                        <td class="fw-bold">{{ number_format(round($item->existencias), 0) }}</td>
                        <td>{{ number_format(round($item->reservado), 0) }}</td>
                        <td>{{ number_format(round($item->remisionado), 0) }}</td>
                        <td class="text-success fw-bold">{{ number_format(round($item->disponible), 0) }}</td>
                        <td class="text-warning fw-bold">{{ number_format(round($item->apartado), 0) }}</td>

                        @if(auth()->user()->role === 'root')
                            <td>
                                <a href="#" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        @endif
                    </tr>

                @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted py-4">
                            No hay registros
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>
</div>

<div class="text-center mt-3">
    <button type="button" id="verMas" class="btn btn-primary">
        Ver más
    </button>
</div>

@stop

@section('js')
<script>
document.addEventListener("DOMContentLoaded", function () {

    const buscador = document.getElementById("buscador");
    const filas = Array.from(document.querySelectorAll("#tablaInventario tr"));
    const cantidadMostrar = document.getElementById("cantidadMostrar");
    const contador = document.getElementById("contador");
    const botonVerMas = document.getElementById("verMas");

    let limite = parseInt(cantidadMostrar.value);
    let filtroActual = "";

    function actualizarTabla() {

        let visibles = 0;
        let mostradas = 0;

        filas.forEach(fila => {
            const texto = fila.innerText.toLowerCase();

            if (texto.includes(filtroActual)) {

                visibles++;

                if (mostradas < limite) {
                    fila.style.display = "";
                    mostradas++;
                } else {
                    fila.style.display = "none";
                }

            } else {
                fila.style.display = "none";
            }
        });

        contador.textContent = visibles;

        if (visibles > limite) {
            botonVerMas.style.display = "inline-block";
        } else {
            botonVerMas.style.display = "none";
        }
    }

    buscador.addEventListener("keyup", function () {
        filtroActual = this.value.toLowerCase();
        limite = parseInt(cantidadMostrar.value);
        actualizarTabla();
    });

    cantidadMostrar.addEventListener("change", function () {
        limite = parseInt(this.value);
        actualizarTabla();
    });

    botonVerMas.addEventListener("click", function () {
        limite += parseInt(cantidadMostrar.value);
        actualizarTabla();
    });

    actualizarTabla();

});
</script>
@stop