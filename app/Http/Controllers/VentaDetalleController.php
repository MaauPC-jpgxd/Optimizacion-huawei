<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VentaDetalle;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\VentasHuaweiExport;
class VentaDetalleController extends Controller

{
    //
public function index(Request $request)
{
    $query = VentaDetalle::with('producto')
        ->whereIn('sucursal', [390, 400, 500, 600])
        ->whereHas('producto', function ($q) {
            $q->where('descripcion', 'like', '%HUAWEI%');
        });

    // Filtro por fecha
    if ($request->fecha_inicio && $request->fecha_fin) {
        $query->whereBetween('fecha', [
            $request->fecha_inicio,
            $request->fecha_fin
        ]);
    }

    // Filtro por sucursal
    if ($request->sucursal) {
        $query->where('sucursal', $request->sucursal);
    }

    $ventas = $query->orderBy('fecha', 'desc')->get();

    return view('ventas.index', compact('ventas'));
}
public function exportExcel()
{
    $ventas = VentaDetalle::with('producto')
        ->whereIn('sucursal', [390, 400, 500, 600])
        ->whereHas('producto', function ($q) {
            $q->where('descripcion', 'like', '%HUAWEI%');
        })
        ->orderBy('fecha', 'desc')
        ->get();

    return Excel::download(new VentasHuaweiExport($ventas), 'ventas_huawei.xlsx');
}
public function exportPdf()
{
    $ventas = VentaDetalle::with('producto')
        ->whereIn('sucursal', [390, 400, 500, 600])
        ->whereHas('producto', function ($q) {
            $q->where('descripcion', 'like', '%HUAWEI%');
        })
        ->orderBy('fecha', 'desc')
        ->get();

    $pdf = PDF::loadView('exports.ventas_huawei_pdf', compact('ventas'));

    return $pdf->download('ventas_huawei.pdf');
}
}
