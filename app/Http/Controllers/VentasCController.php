<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VentasC;
use App\Exports\VentasExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class VentasCController extends Controller
{
    public function index(Request $request)
    {
        $query = $this->getVentasFiltradas($request);

        // STATS
        $statsQuery = clone $query;

        $totalFacturas = $statsQuery->distinct('MovID')->count('MovID');
        $totalImporte = (clone $query)->sum('Importe');
        $totalGeneral = (clone $query)->sum('Total');

        // PAGINACIÓN
        $ventas = $query
            ->orderBy('FechaEmision', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('ventasc.index', compact(
            'ventas',
            'totalFacturas',
            'totalImporte',
            'totalGeneral'
        ));
    }

    // ================= EXPORT EXCEL =================
   public function exportExcel(Request $request)
    {
            $ventas = $this->getVentasFiltradas($request)
                ->orderBy('FechaEmision', 'desc') // 🔥 CLAVE
                ->get();

            return Excel::download(new VentasExport($ventas), 'ventas_factura.xlsx');
    }

    // ================= EXPORT PDF =================
    public function exportPdf(Request $request)
{
    $ventas = $this->getVentasFiltradas($request)
        ->orderBy('FechaEmision', 'desc') // 🔥 CLAVE
        ->get();

    $pdf = Pdf::loadView('exports.ventas_pdf', compact('ventas'));

    return $pdf->download('ventas_factura.pdf');
}

    // ================= QUERY CENTRAL 🔥 =================
    private function getVentasFiltradas($request)
    {
        $query = VentasC::query();

        if ($request->filled('tipo')) {
            $query->where('Mov', 'like', '%' . $request->tipo . '%');
        }

        if ($request->filled('movid')) {
            $query->where('MovID', 'like', '%' . $request->movid . '%');
        }

        if ($request->filled('sucursal')) {
            $query->where('Sucursal', $request->sucursal);
        }

        if ($request->filled('fecha_inicio') && $request->filled('fecha_fin')) {
            $query->whereBetween('FechaEmision', [
                $request->fecha_inicio,
                $request->fecha_fin
            ]);
        }

        if ($request->filled('importe_min')) {
            $query->where('Importe', '>=', $request->importe_min);
        }

        if ($request->filled('importe_max')) {
            $query->where('Importe', '<=', $request->importe_max);
        }

        return $query;
    }
}