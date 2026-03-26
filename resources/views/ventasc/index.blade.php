@extends('adminlte::page')

@section('title', 'Ventas Factura')

@section('content_header')
    <h1 class="text-center font-weight-bold">
        <i class="fas fa-file-invoice-dollar"></i> Ventas Factura
    </h1>
    <!--
    <div class="bg-warning text-dark p-2 mt-2 rounded overflow-hidden">
    <div style="white-space: nowrap; display: inline-block; animation: mover 10s linear infinite;">
        ⚠️ Este módulo está en proceso de actualización y desarrollo, lamentamos las molestias.
    </div>
</div>

<style>
@keyframes mover {
    0% { transform: translateX(100%); }
    100% { transform: translateX(-100%); }
}
</style>-->
@stop

@section('content')

{{-- 🔥 TARJETAS --}}
<div class="row text-center mb-3">
    <div class="col-md-4">
        <div class="small-box bg-info shadow">
            <div class="inner">
                <h3>{{ $totalFacturas }}</h3>
                <p>Total Facturas</p>
            </div>
            <div class="icon">
                <i class="fas fa-file"></i>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="small-box bg-success shadow">
            <div class="inner">
                <h3>${{ number_format($totalImporte, 2) }}</h3>
                <p>Total Importe</p>
            </div>
            <div class="icon">
                <i class="fas fa-coins"></i>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="small-box bg-warning shadow">
            <div class="inner">
                <h3>${{ number_format($totalGeneral, 2) }}</h3>
                <p>Total General</p>
            </div>
            <div class="icon">
                <i class="fas fa-dollar-sign"></i>
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
                    <input type="text" name="movid" class="form-control"
                        placeholder="Ingrese Folio" value="{{ request('movid') }}">
                </div>

                <div class="col-md-2 mb-2">
                    <select name="tipo" class="form-control">
                        <option value="">Tipo</option>
                        <option value="Factura Electronica" {{ request('tipo') == 'Factura Electronica' ? 'selected' : '' }}>
                            Factura Electronica
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
                        <div class="col-md-2 mb-2">
                        <select name="sucursal" class="form-control">
                            <option value="">Sucursal</option>
                            <option value="400" {{ request('sucursal') == '400' ? 'selected' : '' }}>400 HUAWEI ANTENAS OPTI</option>
                            <option value="500" {{ request('sucursal') == '500' ? 'selected' : '' }}>500 HUAWEI NEZA OPTI</option>
                            <option value="600" {{ request('sucursal') == '600' ? 'selected' : '' }}>600 HUAWEI TEZONTLE OPTI</option>
                        </select>
                    </div>
                <div class="col-md-2 mb-2">
                    <input type="number" step="0.01" name="importe_min" class="form-control"
                        placeholder="Min $" value="{{ request('importe_min') }}">
                </div>
                <div class="col-md-2 mb-2">
                    <input type="number" step="0.01" name="importe_max" class="form-control"
                        placeholder="Max $" value="{{ request('importe_max') }}">
                </div>

                <div class="col-md-12 mt-2 text-center">
                    <button class="btn btn-primary">
                        <i class="fas fa-search"></i> Filtrar
                    </button>

                    <a href="{{ url('ventasc') }}" class="btn btn-secondary">
                        <i class="fas fa-broom"></i> Limpiar
                    </a>
                </div>
             

            </div>
        </form>
    </div>
</div>

   <a href="{{ route('ventasc.excel', request()->query()) }}" 
   class="btn btn-success mr-2 shadow">
    <i class="fas fa-file-excel"></i> Excel
</a>
 <a href="{{ route('ventasc.pdf', request()->query()) }}" 
   class="btn btn-danger shadow">
    <i class="fas fa-file-pdf"></i> PDF
</a><br>
{{-- 📊 TABLA --}}
<div class="card shadow-sm">
    <div class="card-body p-0">

        <div class="table-responsive-sm">
            <table class="table table-hover table-striped mb-0">

                <thead class="thead-dark text-center">
                    <tr>
                        <th>#</th>
                        <th>Folio</th>
                        <th>Tipo</th>
                        <th>Sucursal</th>
                        <th>Almacen</th>
                        <th>Fecha</th>
                        <th>Importe</th>
                        <th>Total</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($ventas as $index => $v)
                    <tr class="text-center">
                        <td>{{ $ventas->firstItem() + $index }}</td>

                        <td>
                            <span class="badge badge-info">
                                {{ $v->MovID }}
                            </span>
                        </td>

                        <td>
                            <span class="badge badge-{{ $v->Mov == 'Nota' ? 'warning' : 'success' }}">
                                {{ $v->Mov }}
                            </span>
                        </td>
                                                <td>
                            <span class="badge badge-secondary">
                                {{ $v->Sucursal }}
                            </span>
                        </td>

                        <td>
                            <span class="badge badge-dark">
                                {{ $v->Almacen }}
                            </span>
                        </td>

                        <td>
                            {{ $v->FechaEmision 
                                ? \Carbon\Carbon::parse($v->FechaEmision)->format('d/m/Y') 
                                : '-' }}
                        </td>

                        <td>
                            ${{ number_format($v->Importe ?? 0, 2) }}
                        </td>

                        <td>
                            <strong>${{ number_format($v->Total ?? 0, 2) }}</strong>
                        </td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">
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