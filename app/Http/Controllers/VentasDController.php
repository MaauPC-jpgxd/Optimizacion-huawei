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
        $query = $this->getVentasFiltradas($request);

        // 🔥 STATS
        $statsQuery = clone $query;

        $totalFacturas = $statsQuery->distinct('MovID')->count('MovID');

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

        // 🔥 PAGINACIÓN (CLAVE)
        $ventas = $query
            ->orderBy('FechaEmision', 'desc')
            ->paginate(20)
            ->withQueryString(); // 🔥 IMPORTANTE

        return view('ventasd.index', compact(
            'ventas',
            'totalFacturas',
            'totalCantidad'
        ));
    }

    // ================= EXPORT EXCEL =================
    public function exportExcel(Request $request)
    {
        $ventas = $this->getVentasFiltradas($request)
    ->orderBy('FechaEmision', 'desc') // 🔥 ORDEN REAL
    ->get();

    return Excel::download(new VentasDExport($ventas), 'ventas_detalle.xlsx');
    }

    // ================= EXPORT PDF =================
  public function exportPDF(Request $request)
{
    $ventas = $this->getVentasFiltradas($request)
        ->orderBy('FechaEmision', 'desc') // 🔥 MISMO ORDEN QUE LA VISTA
        ->get();

    $pdf = Pdf::loadView('ventasd.pdf', compact('ventas'))
        ->setPaper('A4', 'landscape');

    return $pdf->download('ventas_detalle.pdf');
}

    // ================= QUERY CENTRAL 🔥 =================
   private function getVentasFiltradas($request)
{
    $query = \DB::table('Ventas_d'); 

    if ($request->filled('sucursal')) {
        $query->where('Sucursal', $request->sucursal);
    }

    if ($request->filled('articulo')) {
        $buscar = $request->articulo;

        $query->where(function ($q) use ($buscar) {
            $q->where('Articulo', 'like', "%$buscar%")
            ->orWhere('ArtDescripcion', 'like', "%$buscar%");
        });
            }

    if ($request->filled('cliente')) {
        $query->where('Cliente', 'like', '%' . $request->cliente . '%');
    }

    if ($request->filled('estatus')) {
        $query->where('Estatus', $request->estatus);
    }

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

    return $query;
}
}