@extends('adminlte::page')

@php
use Illuminate\Support\Str;
@endphp

@section('title', 'Ventas Detalle')

@section('content_header')
    <h1 class="m-0 text-dark text-center font-weight-bold">
        <i class="fas fa-chart-line"></i> Ventas Detalle
    </h1>
@stop

@section('content')

{{-- 🔥 TARJETAS --}}
<div class="row mb-3 text-center">
    <div class="col-md-6">
        <div class="small-box bg-info shadow">
            <div class="inner">
                <h3>{{ $totalFacturas }}</h3>
                <p>Total Facturas</p>
            </div>
            <div class="icon">
                <i class="fas fa-file-invoice"></i>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="small-box bg-success shadow">
            <div class="inner">
                <h3>{{ number_format($totalCantidad, 2) }}</h3>
                <p>Productos Vendidos</p>
            </div>
            <div class="icon">
                <i class="fas fa-shopping-bag"></i>
            </div>
        </div>
    </div>
</div>

{{-- 🔍 FILTROS --}}
<div class="card card-primary card-outline shadow-sm">
    <div class="card-header text-center">
        <h3 class="card-title w-100">
            <i class="fas fa-filter"></i> Filtros
        </h3>
    </div>

    <div class="card-body">
        <form method="GET">
            <div class="row">

                <div class="col-md-2 mb-2">
                    <input type="text" name="articulo" class="form-control"
                        placeholder="Artículo" value="{{ request('articulo') }}">
                </div>

                <div class="col-md-2 mb-2">
                    <input type="text" name="cliente" class="form-control"
                        placeholder="Cliente" value="{{ request('cliente') }}">
                </div>

                <div class="col-md-2 mb-2">
                    <select name="sucursal" class="form-control">
                        <option value="">Sucursal</option>
                        <option value="390" {{ request('sucursal') == '390' ? 'selected' : '' }}>390 MATRIZ HUAWEI OPTI</option>
                        <option value="400" {{ request('sucursal') == '400' ? 'selected' : '' }}>400 HUAWEI ANTENAS OPTI</option>
                        <option value="500" {{ request('sucursal') == '500' ? 'selected' : '' }}>500 HUAWEI NEZA OPTI</option>
                        <option value="600" {{ request('sucursal') == '600' ? 'selected' : '' }}>600 HUAWEI TEZONTLE OPTI</option>
                    </select>
                </div>

                <div class="col-md-2 mb-2">
                    <select name="tipo" class="form-control">
                        <option value="">Tipo</option>
                        <option value="Factura Electronica" {{ request('tipo') == 'Factura Electronica' ? 'selected' : '' }}>
                            Factura Electrónica
                        </option>
                        <option value="Nota" {{ request('tipo') == 'Nota' ? 'selected' : '' }}>
                            Nota
                        </option>
                    </select>
                </div>

                <div class="col-md-2 mb-2">
                    <input type="date" name="fecha_inicio" class="form-control"
                        value="{{ request('fecha_inicio') }}">
                </div>

                <div class="col-md-2 mb-2">
                    <input type="date" name="fecha_fin" class="form-control"
                        value="{{ request('fecha_fin') }}">
                </div>

                <div class="col-md-12 mt-2 text-center">
                    <button class="btn btn-primary">
                        <i class="fas fa-search"></i> Filtrar
                    </button>

                    <a href="{{ url('ventasd') }}" class="btn btn-secondary">
                        <i class="fas fa-broom"></i> Limpiar
                    </a>
                </div>

            </div>
        </form>
    </div>
</div>
<div class="d-flex justify-content-end mb-3">

   <a href="{{ route('ventasd.excel', request()->query()) }}" 
   class="btn btn-success mr-2 shadow">
    <i class="fas fa-file-excel"></i> Excel
</a>

 <a href="{{ route('ventasd.pdf', request()->query()) }}" 
   class="btn btn-danger shadow">
    <i class="fas fa-file-pdf"></i> PDF
</a>

</div>

{{-- 📊 TABLA --}}
<div class="card shadow-sm">
    <div class="card-body p-0">

        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0">

                <thead class="thead-dark text-center">
                    <tr>
                        <th>#</th>
                        <th>Suc</th>
                        <th>Articulo</th>
                        <th>Descripcion</th>
                        <th>Cant</th>
                        <th class="d-none d-lg-table-cell">Cliente</th>
                        <th class="d-none d-lg-table-cell">Nombre</th>
                        <th class="d-none d-xl-table-cell">Agente</th>
                        <th>Factura</th>
                        <th>Estatus</th>
                        <th class="d-none d-md-table-cell">Tipo</th>
                        <th class="d-none d-md-table-cell">Alm</th>
                        <th>Fecha</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($ventas as $index => $v)
                    <tr class="text-center">

                        <td>{{ $ventas->firstItem() + $index }}</td>

                        <td>
                            <span class="badge badge-info">{{ $v->Sucursal }}</span>
                        </td>

                        <td>
                            <small>{{ $v->Articulo }}</small>
                        </td>

                        {{-- 🔥 DESCRIPCIÓN COMPLETA --}}
                        <td style="max-width: 300px; white-space: normal; word-wrap: break-word;">
                            <small>{{ $v->ArtDescripcion }}</small>
                        </td>

                        <td>
                            <strong>{{ $v->Cantidad }}</strong>
                        </td>

                        <td class="d-none d-lg-table-cell">
                            <small>{{ $v->Cliente }}</small>
                        </td>

                        <td class="d-none d-lg-table-cell">
                            <small>{{ $v->CteNombre }}</small>
                        </td>

                        <td class="d-none d-xl-table-cell">
                            <small>{{ $v->Agente }}</small>
                        </td>

                        <td>
                            <small>{{ $v->MovID }}</small>
                        </td>

                        <td>
                            <span class="badge badge-{{ $v->Estatus == 'CONCLUIDO' ? 'success' : 'warning' }}">
                                {{ $v->Estatus }}
                            </span>
                        </td>

                        <td class="d-none d-md-table-cell">
                            <small>{{ $v->Mov }}</small>
                        </td>

                        <td class="d-none d-md-table-cell">
                            <small>{{ $v->Almacen }}</small>
                        </td>

                        <td>
                            <small>{{ \Carbon\Carbon::parse($v->FechaEmision)->format('d/m/Y') }}</small>
                        </td>

                    </tr>

                    @empty
                    <tr>
                        <td colspan="13" class="text-center text-muted">
                            No hay resultados
                        </td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

    </div>

    <div class="card-footer text-center">
        {{ $ventas->links('pagination::bootstrap-4') }}
    </div>
</div>

@stop