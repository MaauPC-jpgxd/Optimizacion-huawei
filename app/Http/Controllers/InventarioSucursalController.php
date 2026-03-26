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
    public function index(Request $request)
    {
        $query = InventarioSucursal::with('producto');

        // 🔥 FILTRO HUAWEI (MUCHO MÁS FLEXIBLE)
        $query->where(function ($q) {
            $q->whereHas('producto', function ($q2) {
                $q2->where('descripcion', 'like', '%HUAWEI%');
            })
            ->orWhere('articulo', 'like', '%HUAWEI%'); // fallback 🔥
        });

        // 🔍 SUCURSAL
        if ($request->filled('sucursal')) {
            $query->whereRaw('FLOOR(almacen) = ?', [$request->sucursal]);
        }

        // 🔍 BUSCADOR
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;

            $query->where(function ($q) use ($buscar) {
                $q->where('articulo', 'like', "%$buscar%")
                  ->orWhereHas('producto', function ($q2) use ($buscar) {
                      $q2->where('descripcion', 'like', "%$buscar%");
                  });
            });
        }

        // 🔥 SOLO PRODUCTOS REALES
        $query->where(function ($q) {
            $q->where('existencias', '>', 0)
              ->orWhere('disponible', '>', 0);
        });

        $inventarios = $query
            ->orderByDesc('existencias')
            ->paginate(15)
            ->withQueryString();

        // 📊 CARDS
        $totalProductos = $inventarios->total();
        $totalDisponible = $query->sum('disponible');
        $totalExistencias = $query->sum('existencias');

        $sucursales = Sucursal::all();

        return view('inventario.index', compact(
            'inventarios',
            'sucursales',
            'totalProductos',
            'totalDisponible',
            'totalExistencias'
        ));
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(
            new InventarioExport($this->getData($request)),
            'inventario_huawei.xlsx'
        );
    }

    public function exportPdf(Request $request)
    {
        $data = $this->getData($request);
        $inventarios = $this->getData($request);
        $pdf = Pdf::loadView('exports.inventario_pdf', compact('inventarios'));
        return $pdf->download('inventario_huawei.pdf');
    }

    private function getData($request)
    {
        return InventarioSucursal::with('producto')->get();
    }
}