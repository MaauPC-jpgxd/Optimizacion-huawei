@extends('adminlte::page')

@section('title', 'Centro de Inteligencia')

@section('content_header')
<h1 class="font-weight-bold text-center">Estadísticas</h1>

<div class="bg-warning text-dark p-2 mt-2 rounded overflow-hidden">
    <div class="marquee-text">
        ⚠️ Este módulo está en proceso de desarrollo, lamentamos las molestias.
    </div>
</div>

<style>
.marquee-text {
    white-space: nowrap;
    display: inline-block;
    animation: mover 14s linear infinite;
}
@keyframes mover {
    0% { transform: translateX(100%); }
    100% { transform: translateX(-100%); }
}
</style>
@stop

@section('content')

{{-- ================= KPIs ================= --}}
<div class="row mb-3">

    <div class="col-xl-3 col-md-6 mb-2">
        <div class="small-box bg-danger shadow">
            <div class="inner">
                <h3>{{ $criticos->count() }}</h3>
                <p>Artículos Críticos</p>
            </div>
            <div class="icon"><i class="fas fa-fire"></i></div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-2">
        <div class="small-box bg-warning shadow">
            <div class="inner">
                <h3>{{ $articulos->total() }}</h3>
                <p>Alertas</p>
            </div>
            <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-2">
        <div class="small-box bg-info shadow">
            <div class="inner">
                <h3>${{ number_format($ultimaVenta->total ?? 0, 2) }}</h3>
                <p>Última venta: {{ $ultimaVenta->fecha ?? 'N/A' }}</p>
            </div>
            <div class="icon"><i class="fas fa-chart-line"></i></div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-2">
        <div class="small-box bg-success shadow">
            <div class="inner">
                <h3>AI</h3>
                <p>Predicción</p>
            </div>
            <div class="icon"><i class="fas fa-brain"></i></div>
        </div>
    </div>

</div>

{{-- ================= GRID ================= --}}
<div class="row">

    {{-- CRÍTICOS --}}
    <div class="col-xl-4 col-lg-5 mb-3">
        <div class="card shadow h-100">
            <div class="card-header bg-danger text-white">
                Críticos
            </div>

            <div class="card-body" style="max-height:340px; overflow:auto;">
                @forelse($criticos as $c)
                    <div class="border-bottom py-2">
                        <strong>{{ $c->Articulo }}</strong><br>
                        <small>{{ $c->Descripcion1 }}</small><br>

                        <span class="badge badge-danger">
                            {{ round($c->dias_restantes,1) }} días
                        </span>
                        <span class="badge badge-secondary">
                            {{ number_format($c->existencias) }} stock
                        </span>
                    </div>
                @empty
                    <div class="text-center text-muted">Sin críticos 🎉</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- INVENTARIO --}}
    <div class="col-xl-8 col-lg-7 mb-3">
        <div class="card shadow">
            <div class="card-header bg-warning">
                Inventario inteligente
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-hover text-center mb-0">
                        <thead class="bg-dark text-white">
                            <tr>
                                <th>Artículo</th>
                                <th>Descripción</th>
                                <th>Stock</th>
                                <th>Consumo</th>
                                <th>Días</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($articulos as $a)
                            <tr>
                                <td><span class="badge badge-info">{{ $a->Articulo }}</span></td>
                                <td class="text-left">{{ $a->Descripcion1 }}</td>
                                <td>{{ number_format($a->existencias) }}</td>
                                <td>{{ number_format($a->promedio_diario,2) }}</td>
                                <td>
                                    <span class="badge {{ $a->dias_restantes <= 5 ? 'badge-danger' : 'badge-warning' }}">
                                        {{ round($a->dias_restantes,1) }}
                                    </span>
                                </td>
                                <td>
                                    {{ $a->dias_restantes <= 5 ? 'Urgente' : 'Reponer' }}
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6">Sin datos</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- ================= GRÁFICA ================= --}}
{{-- ================= GRÁFICA ================= --}}
<div class="row">
    <div class="col-xl-6">
        <div class="card shadow">
            <div class="card-header bg-info text-white">
                Ventas por mes
            </div>
            <div class="card-body">
                {{-- El wrapper con altura es obligatorio para maintainAspectRatio:false --}}
                <div style="position:relative; height:260px;">
                    <canvas id="ventasChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ================= MODAL ================= --}}
<div id="modalGrafica" style="
    display:none;
    position:fixed;
    inset:0;
    background:rgba(0,0,0,.75);
    z-index:9999;
    justify-content:center;
    align-items:center;
">
    <div style="background:#fff; padding:20px; border-radius:10px; width:82%; max-width:900px;">
        <h4 class="mb-3">Ventas por mes</h4>

        {{-- Wrapper con altura explícita también aquí --}}
        <div style="position:relative; height:380px;">
            <canvas id="ventasChartModal"></canvas>
        </div>

        <button class="btn btn-danger btn-block mt-3" onclick="cerrarModal()">Cerrar</button>
    </div>
</div>

{{-- ================= SCRIPT ================= --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Blade vuelca el JSON; si el controlador no pasó la variable, queda []
const ventasPorMes = @json($ventasPorMes ?? []);

function safeData(raw) {
    return raw.map(v => ({
        fecha: v.fecha ?? v.mes ?? '',   // acepta 'fecha' o 'mes' como clave
        total: Number(v.total ?? 0)
    })).filter(v => v.fecha !== '');
}

function buildChart(canvasId, datos) {
    return new Chart(document.getElementById(canvasId), {
        type: 'line',
        data: {
            labels: datos.map(v => v.fecha),
            datasets: [{
                label: 'Ventas ($)',
                data: datos.map(v => v.total),
                borderColor: '#3498db',
                backgroundColor: 'rgba(52,152,219,.15)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,   // requiere wrapper con altura fija
            scales: {
                y: { beginAtZero: true }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });
}

let chartInline = null;
let chartModal  = null;

document.addEventListener('DOMContentLoaded', () => {

    const data = safeData(ventasPorMes);

    // ── Diagnóstico rápido (quita esto en producción) ──────────────
    console.log('ventasPorMes →', data);

    if (data.length === 0) {
        console.warn('Sin datos para graficar. Revisa que el controlador pase $ventasPorMes con campos "fecha" y "total".');
        return;
    }

    // ── Gráfica inline ─────────────────────────────────────────────
    chartInline = buildChart('ventasChart', data);

    // ── Click para abrir modal ─────────────────────────────────────
    document.getElementById('ventasChart').addEventListener('click', () => {
        abrirModal(data);
    });
});

function abrirModal(data) {
    document.getElementById('modalGrafica').style.display = 'flex';

    if (chartModal) {
        chartModal.destroy();
        chartModal = null;
    }

    // Pequeño delay para que el modal termine de pintarse antes de dibujar
    setTimeout(() => {
        chartModal = buildChart('ventasChartModal', data);
    }, 50);
}

function cerrarModal() {
    document.getElementById('modalGrafica').style.display = 'none';
}

// Cierra modal al hacer click en el overlay (fuera del cuadro)
document.getElementById('modalGrafica').addEventListener('click', function(e) {
    if (e.target === this) cerrarModal();
});
</script>
{{-- ================= MODAL ================= --}}
<div id="modalGrafica" style="
    display:none;
    position:fixed;
    inset:0;
    background:rgba(0,0,0,.75);
    z-index:9999;
    justify-content:center;
    align-items:center;
">

    <div style="background:#fff; padding:20px; border-radius:10px; width:80%;">
        <h4>Ventas por mes</h4>
        <canvas id="ventasChartModal" style="height:400px;"></canvas>

        <button class="btn btn-danger mt-3" onclick="cerrarModal()">
            Cerrar
        </button>
    </div>
</div>

{{-- ================= SCRIPT ================= --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const ventasPorMes = @json($ventasPorMes ?? []);

function safeData(data){
    return data.map(v => ({
        fecha: v.fecha,
        total: Number(v.total ?? 0)
    }));
}

function buildChart(datos){
    return {
        type: 'line',
        data: {
            labels: datos.map(v => v.fecha),
            datasets: [{
                label: 'Ventas',
                data: datos.map(v => v.total),
                borderColor: '#3498db',
                backgroundColor: 'rgba(52,152,219,.15)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true }
            }
        }
    };
}

let chart1, chart2;

document.addEventListener("DOMContentLoaded", () => {

    const data = safeData(ventasPorMes);

    if(data.length > 0){
        chart1 = new Chart(
            document.getElementById('ventasChart'),
            buildChart(data)
        );
    }

    document.getElementById('ventasChart').onclick = () => {
        document.getElementById('modalGrafica').style.display = 'flex';

        if(chart2) chart2.destroy();

        chart2 = new Chart(
            document.getElementById('ventasChartModal'),
            buildChart(data)
        );
    };

});

function cerrarModal(){
    document.getElementById('modalGrafica').style.display = 'none';
}
</script>

@stop