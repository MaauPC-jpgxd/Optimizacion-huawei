@extends('adminlte::page')

@section('title', 'Ventas Huawei')

@section('content_header')
    <h1 class="fw-bold text-center">Ventas Generales Huawei</h1>
@stop

@section('content')

<div class="card shadow-sm mb-4">
    <div class="card-body">

        <form method="GET" action="{{ route('ventas.huawei') }}">

            <div class="row align-items-end text-center g-3">

                <!-- Fecha inicio -->
                <div class="col-md-2">
                    <label class="fw-bold">Desde</label>
                    <input type="date"
                           name="fecha_inicio"
                           value="{{ request('fecha_inicio') }}"
                           class="form-control">
                </div>

                <!-- Fecha fin -->
                <div class="col-md-2">
                    <label class="fw-bold">Hasta</label>
                    <input type="date"
                           name="fecha_fin"
                           value="{{ request('fecha_fin') }}"
                           class="form-control">
                </div>

                <!-- Sucursal -->
                <div class="col-md-2">
                    <label class="fw-bold">Sucursal</label>
                    <select name="sucursal"
                            class="form-control"
                            onchange="this.form.submit()">
                        <option value="">Todas</option>
                        <option value="390" {{ request('sucursal') == 390 ? 'selected' : '' }}>390</option>
                        <option value="400" {{ request('sucursal') == 400 ? 'selected' : '' }}>400</option>
                        <option value="500" {{ request('sucursal') == 500 ? 'selected' : '' }}>500</option>
                        <option value="600" {{ request('sucursal') == 600 ? 'selected' : '' }}>600</option>
                    </select>
                </div>

                <!-- Buscar General -->
                <div class="col-md-2">
                    <label class="fw-bold">Buscar General</label>
                    <input type="text"
                           id="buscadorGeneral"
                           class="form-control text-center"
                           placeholder="Buscar todo...">
                </div>

                <!-- Filtro Artículo -->
                <div class="col-md-2">
                    <label class="fw-bold">Filtrar Artículo</label>
                    <input type="text"
                           id="filtroArticulo"
                           class="form-control text-center"
                           placeholder="Solo artículo...">
                </div>

                <!-- Botón -->
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        Aplicar Filtro
                    </button>
                </div>

            </div>

        </form>

        <!-- Segunda fila -->
        <div class="row mt-4 align-items-center text-center">

            <!-- Mostrar -->
            <div class="col-md-3">
                <label class="fw-bold d-block">Mostrar</label>
                <select id="cantidadMostrar"
                        class="form-control form-control-sm text-center mx-auto"
                        style="width:100px;">
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
            </div>

            <!-- Contador -->
            <div class="col-md-3">
                <label class="fw-bold d-block">Resultados</label>
                <span class="badge bg-primary p-2">
                    <span id="contador">{{ $ventas->count() }}</span>
                </span>
            </div>

            <!-- Total -->
            <div class="col-md-3">
                <label class="fw-bold d-block">Total General</label>
                <span class="badge bg-success p-2">
                    ${{ number_format($ventas->sum('monto'), 2) }}
                </span>
            </div>

            <!-- Exportar -->
            <div class="col-md-3">
                <label class="fw-bold d-block">Exportar Ventas Completas</label>
                <div class="d-flex justify-content-center gap-2">
                    <a href="{{ route('ventas.huawei.excel') }}"
                       class="btn btn-success btn-sm">
                        <i class="fas fa-file-excel"></i> Excel
                    </a>

                    <a href="{{ route('ventas.huawei.pdf') }}"
                       class="btn btn-danger btn-sm">
                        <i class="fas fa-file-pdf"></i> PDF
                    </a>
                </div>
            </div>

        </div>

    </div>
</div>

<!-- TABLA -->
<div class="card shadow-sm">
    <div class="card-body table-responsive p-0">

        <table class="table table-hover table-striped table-sm text-nowrap text-center">
            <thead style="background-color:#0d6efd; color:white">
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
            <tbody id="tablaVentas">
                @forelse($ventas as $venta)
                    <tr>
                        <td class="fw-bold">{{ $venta->sucursal }}</td>
                        <td class="articulo fw-bold">{{ $venta->articulo }}</td>
                        <td>
                            <small class="text-muted">
                                {{ optional($venta->producto)->descripcion ?? 'Sin descripción' }}
                            </small>
                        </td>
                        <td>{{ number_format($venta->cantidad, 0) }}</td>
                        <td>${{ number_format($venta->precio, 2) }}</td>
                        <td class="text-danger">${{ number_format($venta->descuento, 2) }}</td>
                        <td class="text-success fw-bold">${{ number_format($venta->monto, 2) }}</td>
                        <td>{{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">
                            No hay ventas registradas
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>
</div>

<div class="text-center mt-3">
    <button type="button" id="verMas" class="btn btn-success">
        Ver más
    </button>
</div>

@stop

@section('js')
<script>
document.addEventListener("DOMContentLoaded", function () {

    const buscadorGeneral = document.getElementById("buscadorGeneral");
    const filtroArticulo = document.getElementById("filtroArticulo");
    const filas = Array.from(document.querySelectorAll("#tablaVentas tr"));
    const cantidadMostrar = document.getElementById("cantidadMostrar");
    const contador = document.getElementById("contador");
    const botonVerMas = document.getElementById("verMas");

    const fechaInicio = document.querySelector("input[name='fecha_inicio']");
    const fechaFin = document.querySelector("input[name='fecha_fin']");

    let limite = parseInt(cantidadMostrar.value);
    let filtroTexto = "";
    let filtroArt = "";

    const hoy = new Date().toISOString().split("T")[0];

    // Bloquear fechas futuras
    fechaInicio.max = hoy;
    fechaFin.max = hoy;

    // Validación de rango
    fechaInicio.addEventListener("change", function() {
        fechaFin.min = this.value;
    });

    fechaFin.addEventListener("change", function() {
        fechaInicio.max = this.value || hoy;
    });

    function actualizarTabla() {

        let visibles = 0;
        let mostradas = 0;

        filas.forEach(fila => {

            const textoGeneral = fila.innerText.toLowerCase();
            const textoArticulo = fila.querySelector(".articulo")?.innerText.toLowerCase() || "";

            const cumpleGeneral = textoGeneral.includes(filtroTexto);
            const cumpleArticulo = textoArticulo.includes(filtroArt);

            if (cumpleGeneral && cumpleArticulo) {

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
        botonVerMas.style.display = visibles > limite ? "inline-block" : "none";
    }

    buscadorGeneral.addEventListener("keyup", function () {
        filtroTexto = this.value.toLowerCase();
        limite = parseInt(cantidadMostrar.value);
        actualizarTabla();
    });

    filtroArticulo.addEventListener("keyup", function () {
        filtroArt = this.value.toLowerCase();
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