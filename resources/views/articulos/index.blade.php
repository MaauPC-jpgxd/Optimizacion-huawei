@extends('adminlte::page')

@section('title', 'Artículos')

@section('content_header')
    <h1 class="text-center font-weight-bold mb-4">
        <i class="fas fa-box"></i> Inventario
    </h1>
@stop

@section('content')

{{-- 📊 CARDS --}}
{{-- 📊 CARDS GRANDES Y BIEN DISTRIBUIDAS --}}
<div class="row text-center mb-4 justify-content-center">

    <div class="col-6 col-md-4 col-lg-2 mb-3">
        <div class="small-box bg-info shadow h-100 d-flex flex-column justify-content-center">
            <div class="inner">
                <h3 class="display-5">{{ $totalArticulos }}</h3>
                <p>Artículos</p>
            </div>
            <div class="icon" style="font-size: 2.5rem;">
                <i class="fas fa-boxes"></i>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-4 col-lg-2 mb-3">
        <div class="small-box bg-success shadow h-100 d-flex flex-column justify-content-center">
            <div class="inner">
                <h3 class="display-5">${{ number_format($totalCostoPromedio,2) }}</h3>
                <p>Costo Promedio</p>
            </div>
            <div class="icon" style="font-size: 2.5rem;">
                <i class="fas fa-dollar-sign"></i>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-4 col-lg-2 mb-3">
        <div class="small-box bg-primary shadow h-100 d-flex flex-column justify-content-center">
            <div class="inner">
                <h3 class="display-5">{{ number_format($totalExistencias) }}</h3>
                <p>Existencias</p>
            </div>
            <div class="icon" style="font-size: 2.5rem;">
                <i class="fas fa-layer-group"></i>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-4 col-lg-2 mb-3">
        <div class="small-box bg-success shadow h-100 d-flex flex-column justify-content-center">
            <div class="inner">
                <h3 class="display-5">{{ number_format($totalDisponible) }}</h3>
                <p>Disponible</p>
            </div>
            <div class="icon" style="font-size: 2.5rem;">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-4 col-lg-2 mb-3">
        <div class="small-box bg-danger shadow h-100 d-flex flex-column justify-content-center">
            <div class="inner">
                <h3 class="display-5">${{ number_format($totalValorInventario,2) }}</h3>
                <p>Valor Inventario</p>
            </div>
            <div class="icon" style="font-size: 2.5rem;">
                <i class="fas fa-coins"></i>
            </div>
        </div>
    </div>

</div>

{{-- 🔍 FILTROS --}}
<div class="card card-primary card-outline shadow mb-4">
    <div class="card-body">
        <form method="GET">
            <div class="row align-items-center">

                <div class="col-md-3 mb-2">
                    <input type="text" name="buscar" class="form-control shadow-sm"
                        placeholder="🔎 Buscar artículo o descripción"
                        value="{{ request('buscar') }}">
                </div>

                <div class="col-md-2 mb-2">
                    <input type="text" name="categoria" class="form-control shadow-sm"
                        placeholder="Categoría"
                        value="{{ request('categoria') }}">
                </div>

                <div class="col-md-3 mb-2">
                    <select name="almacen" class="form-control shadow-sm">
                        <option value="">-- Almacén --</option>
                        @foreach(['00390.1','00400.1','00400.2','00400.3','00500.1','00500.2','00500.3','00600.1','00600.2','00600.3'] as $alm)
                            <option value="{{ $alm }}" {{ request('almacen') == $alm ? 'selected' : '' }}>
                                {{ $alm }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 mb-2">
                    <button class="btn btn-primary w-100 shadow-sm">
                        <i class="fas fa-search"></i> Buscar
                    </button>
                </div>

                <div class="col-md-2 mb-2">
                    <a href="{{ route('articulos.index') }}" class="btn btn-outline-danger w-100 shadow-sm">
                        <i class="fas fa-broom"></i> Limpiar
                    </a>
                </div>

            </div>
        </form>

        {{-- 🔥 BOTONES EXPORTAR --}}
        <div class="d-flex justify-content-end mt-3 gap-2">
            <a href="{{ route('articulos.excel', request()->all()) }}" 
                class="btn btn-success shadow-sm mr-2">
                <i class="fas fa-file-excel"></i> Excel
            </a>

            <a href="{{ route('articulos.pdf', request()->all()) }}" 
                class="btn btn-danger shadow-sm">
                <i class="fas fa-file-pdf"></i> PDF
            </a>
        </div>

    </div>
</div>

{{-- 📊 TABLA --}}
<div class="card shadow">
    <div class="card-body p-0">

        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0">

                <thead class="bg-dark text-white text-center">
                    <tr>
                        <th>#</th>
                        <th>Artículo</th>
                        <th>Descripción</th>
                        <th>Categoría</th>
                        <th>Fabricante</th>
                        <th>Almacén</th>
                        <th>Existencias</th>
                        <th>Disponible</th>
                        <th>Estatus</th>
                        <th>Costo</th>
                        <th>Valor</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($articulos as $index => $a)
                    <tr class="text-center align-middle">

                        <td>{{ $articulos->firstItem() + $index }}</td>

                        <td>
                            <span class="badge badge-info px-2 py-1">
                                {{ $a->Articulo }}
                            </span>
                        </td>

                        <td class="text-left" style="max-width:250px;">
                            {{ $a->Descripcion1 }}
                        </td>

                        <td>{{ $a->Categoria }}</td>
                        <td>{{ $a->Fabricante }}</td>

                        <td>
                            <span class="badge badge-secondary">
                                {{ $a->Almacen }}
                            </span>
                        </td>

                        <td>{{ number_format($a->existencias ?? 0) }}</td>
                        <td>{{ number_format($a->disponible ?? 0) }}</td>

                        <td>
                            <span class="badge badge-{{ $a->Estatus == 'ALTA' ? 'success' : 'danger' }}">
                                {{ $a->Estatus }}
                            </span>
                        </td>

                        <td>
                            ${{ number_format($a->CostoPromedio ?? 0, 2) }}
                        </td>

                        <td class="font-weight-bold text-success">
                           ${{ number_format(($a->existencias ?? 0) * ($a->CostoPromedio ?? 0), 2) }}
                        </td>

                    </tr>

                    @empty
                    <tr>
                        <td colspan="11" class="text-center text-muted py-3">
                            <i class="fas fa-search"></i> Sin resultados
                        </td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

    </div>

    <div class="card-footer text-center">
        {{ $articulos->appends(request()->all())->links('pagination::bootstrap-4') }}
    </div>
</div>

@stop