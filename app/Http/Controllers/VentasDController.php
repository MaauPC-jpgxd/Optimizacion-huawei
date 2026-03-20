<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VentasD;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\VentasDExport;
use Barryvdh\DomPDF\Facade\Pdf;

class VentasDController extends Controller
{
   public function index(Request $request)
{
    $query = VentasD::query();

    // 🔍 FILTROS
    if ($request->filled('sucursal')) {
        $query->where('Sucursal', $request->sucursal);
    }

    if ($request->filled('articulo')) {
        $query->where('Articulo', 'like', '%' . $request->articulo . '%');
    }

    if ($request->filled('cliente')) {
        $query->where('Cliente', 'like', '%' . $request->cliente . '%');
    }

    if ($request->filled('estatus')) {
        $query->where('Estatus', $request->estatus);
    }

    // 🔥 FILTRO TIPO (FIX REAL)
    if ($request->filled('tipo')) {

        if ($request->tipo == 'Factura Electronica') {
            $query->where('Mov', 'like', '%Factura%');
        }

        if ($request->tipo == 'Nota') {
            $query->where('Mov', 'like', '%Nota%');
        }
    }

    if ($request->filled('fecha_inicio') && $request->filled('fecha_fin')) {
        $query->whereBetween('FechaEmision', [
            $request->fecha_inicio,
            $request->fecha_fin
        ]);
    }

    // 🔥 CLON PARA ESTADÍSTICAS
    $statsQuery = clone $query;

    // 📊 TOTAL FACTURAS
    $totalFacturas = $statsQuery->distinct('MovID')->count('MovID');

    // 📊 TOTAL CANTIDAD (SQL SERVER FIX)
    $totalCantidad = (clone $query)
        ->selectRaw("
            SUM(
                CASE 
                    WHEN ISNUMERIC(Cantidad) = 1 
                    THEN CAST(Cantidad AS DECIMAL(18,2)) 
                    ELSE 0 
                END
            ) as total
        ")
        ->value('total');

    // 🔹 DATOS PRINCIPALES
    $ventas = $query->orderBy('FechaEmision', 'desc')->paginate(20);

    return view('ventasd.index', compact(
        'ventas',
        'totalFacturas',
        'totalCantidad'
    ));
}

    // (Aún no usados pero listos)
    public function exportExcel(Request $request)
    {
        return Excel::download(new VentasDExport($request), 'ventas.xlsx');
    }

   public function exportPDF(Request $request)
{
    $query = VentasD::query();

    // 🔍 FILTROS (igual que index)
    if ($request->filled('sucursal')) {
        $query->where('Sucursal', $request->sucursal);
    }

    if ($request->filled('articulo')) {
        $query->where('Articulo', 'like', '%' . $request->articulo . '%');
    }

    if ($request->filled('cliente')) {
        $query->where('Cliente', 'like', '%' . $request->cliente . '%');
    }

    if ($request->filled('estatus')) {
        $query->where('Estatus', $request->estatus);
    }

    if ($request->filled('tipo')) {
        $query->where('Mov', 'like', '%' . $request->tipo . '%');
    }

    if ($request->filled('fecha_inicio') && $request->filled('fecha_fin')) {
        $query->whereBetween('FechaEmision', [
            $request->fecha_inicio,
            $request->fecha_fin
        ]);
    }

    $ventas = $query->get();

    //$pdf = Pdf::loadView('ventasd.pdf', compact('ventas'));
    $pdf = Pdf::loadView('ventasd.pdf', compact('ventas'))
          ->setPaper('A4', 'landscape');

    return $pdf->download('ventas.pdf');
}
}