<?php

namespace App\Http\Controllers;

use App\Models\InventarioSucursal;
use App\Models\Sucursal;
use Illuminate\Http\Request;
use App\Exports\InventarioExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class InventarioSucursalController extends Controller
{
    // ================= INDEX =================
    public function index(Request $request)
    {
        $query = $this->getQuery($request);

        // 🔥 PAGINACIÓN CON FILTROS
        $inventarios = $query
            ->orderByDesc('existencias')
            ->paginate(15)
            ->withQueryString();

        // 📊 CARDS (CLONAR QUERY)
        $totalProductos = $inventarios->total();
        $totalDisponible = (clone $query)->sum('disponible');
        $totalExistencias = (clone $query)->sum('existencias');

        $sucursales = Sucursal::all();

        return view('inventario.index', compact(
            'inventarios',
            'sucursales',
            'totalProductos',
            'totalDisponible',
            'totalExistencias'
        ));
    }

    // ================= EXPORT EXCEL =================
    public function exportExcel(Request $request)
    {
        $data = $this->getQuery($request)->get();

        return Excel::download(
            new InventarioExport($data),
            'inventario_huawei.xlsx'
        );
    }

    // ================= EXPORT PDF =================
    public function exportPdf(Request $request)
    {
        $inventarios = $this->getQuery($request)->get();

        $pdf = Pdf::loadView('exports.inventario_pdf', compact('inventarios'));

        return $pdf->download('inventario_huawei.pdf');
    }

    // ================= QUERY CENTRAL 🔥 =================
    private function getQuery($request)
    {
        $query = InventarioSucursal::with('producto');

        // 🔥 FILTRO HUAWEI (ROBUSTO)
        $query->where(function ($q) {
            $q->whereHas('producto', function ($q2) {
                $q2->whereNotNull('descripcion')
                   ->whereRaw("LTRIM(RTRIM(descripcion)) <> ''")
                   ->where('descripcion', 'like', '%HUAWEI%')
                   ->whereRaw("LOWER(descripcion) NOT LIKE '%compatible%'")
                   ->whereRaw("LOWER(descripcion) NOT LIKE '%generico%'");
            })
            ->orWhere('articulo', 'like', '%HUAWEI%'); // fallback 🔥
        });

        // 🔍 FILTRO SUCURSAL
        if ($request->filled('sucursal')) {
            $query->whereRaw('FLOOR(almacen) = ?', [$request->sucursal]);
        }

        // 🔍 BUSCADOR GLOBAL
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;

            $query->where(function ($q) use ($buscar) {
                $q->where('articulo', 'like', "%$buscar%")
                  ->orWhereHas('producto', function ($q2) use ($buscar) {
                      $q2->where('descripcion', 'like', "%$buscar%");
                  });
            });
        }

        // 🔥 SOLO PRODUCTOS CON STOCK REAL
        $query->where(function ($q) {
            $q->where('existencias', '>', 0)
              ->orWhere('disponible', '>', 0);
        });

        return $query;
    }
}