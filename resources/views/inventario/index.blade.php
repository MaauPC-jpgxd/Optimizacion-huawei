@extends('adminlte::page')

@section('title', 'Inventario Huawei')

@section('content')

{{-- 🔥 CARDS --}}
<div class="row text-center mb-3">

    <div class="col-md-4">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $totalProductos }}</h3>
                <p>Productos</p>
            </div>
            <div class="icon"><i class="fas fa-box"></i></div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ number_format($totalDisponible) }}</h3>
                <p>Disponible</p>
            </div>
            <div class="icon"><i class="fas fa-check"></i></div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ number_format($totalExistencias) }}</h3>
                <p>Existencias</p>
            </div>
            <div class="icon"><i class="fas fa-warehouse"></i></div>
        </div>
    </div>

</div>

{{-- 🔍 FILTROS --}}
<div class="card card-primary card-outline">
    <div class="card-body">
        <form method="GET">

            <div class="row text-center">

                <div class="col-md-3">
                    <select name="sucursal" class="form-control">
                        <option value="">Sucursal</option>
                        @foreach($sucursales as $s)
                            <option value="{{ $s->clave }}"
                                {{ request('sucursal') == $s->clave ? 'selected' : '' }}>
                                {{ $s->clave }} - {{ $s->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <input type="text" name="buscar" class="form-control"
                        value="{{ request('buscar') }}" placeholder="Buscar...">
                </div>

                <div class="col-md-3">
                    <button class="btn btn-primary">
                        <i class="fas fa-search"></i> Buscar
                    </button>

                    <a href="{{ route('inventario.index') }}" class="btn btn-secondary">
                        <i class="fas fa-broom"></i>
                    </a>
                </div>

                <div class="col-md-3">
                    <a href="{{ route('inventario.excel', request()->query()) }}" class="btn btn-success">
                        <i class="fas fa-file-excel"></i>
                    </a>

                    <a href="{{ route('inventario.pdf', request()->query()) }}" class="btn btn-danger">
                        <i class="fas fa-file-pdf"></i>
                    </a>
                </div>

            </div>

        </form>
    </div>
</div>

{{-- 📊 TABLA --}}
<div class="card mt-3">
    <div class="card-body p-0">
        <table class="table table-striped text-center">

            <thead class="thead-dark">
                <tr>
                    <th>Sucursal</th>
                    <th>Almacén</th>
                    <th>Artículo</th>
                    <th>Descripción</th>
                    <th>Disponible</th>
                </tr>
            </thead>

            <tbody>
                @foreach($inventarios as $item)

                    @php
                        $descripcion = $item->producto->descripcion 
                            ?? $item->descripcion 
                            ?? $item->articulo; // 🔥 fallback FINAL
                    @endphp

                    <tr>
                        <td>{{ floor($item->almacen) }}</td>
                        <td>{{ ltrim($item->almacen,'0') }}</td>
                        <td>{{ $item->articulo }}</td>
                        <td>{{ $descripcion }}</td>
                        <td class="text-success">{{ $item->disponible }}</td>
                    </tr>

                @endforeach
            </tbody>

        </table>
    </div>

    <div class="card-footer text-center">
        {{ $inventarios->links('pagination::bootstrap-4') }}
    </div>
</div>

@stop