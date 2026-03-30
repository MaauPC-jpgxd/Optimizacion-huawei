<?php

namespace App\Http\Controllers;
use App\Exports\ArticulosExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Models\Articulo;
use Barryvdh\DomPDF\Facade\Pdf;
class ArticuloController extends Controller
{
public function index(Request $request)
{
    $query = Articulo::query()
        ->leftJoin('inventario_sucursales as inv', function ($join) {
            $join->on('articulos.Articulo', '=', 'inv.articulo')
                 ->on('articulos.Almacen', '=', 'inv.almacen');
        });

    // 🔥 FILTRO GLOBAL: solo con existencias > 0
    $query->whereRaw('ISNULL(inv.existencias,0) > 0');

    // 🔍 BUSCADOR
    if ($request->filled('buscar')) {
        $buscar = $request->buscar;

        $query->where(function ($q) use ($buscar) {
            $q->where('articulos.Articulo', 'like', "%$buscar%")
              ->orWhere('articulos.Descripcion1', 'like', "%$buscar%");
        });
    }

    // 🔍 FILTROS
    if ($request->filled('categoria')) {
        $query->where('articulos.Categoria', $request->categoria);
    }

    if ($request->filled('fabricante')) {
        $query->where('articulos.Fabricante', $request->fabricante);
    }

    if ($request->filled('almacen')) {
        $query->where('articulos.Almacen', $request->almacen);
    }

    // =========================
    // 🔥 TOTALES
    // =========================
    /*
    $totales = (clone $query)
        ->selectRaw('
            COUNT(*) as total,
            SUM(ISNULL(articulos.CostoPromedio,0)) as totalCostoPromedio,
            SUM(ISNULL(articulos.UltimoCosto,0)) as totalUltimoCosto,
            SUM(ISNULL(inv.existencias,0)) as totalExistencias,
            SUM(ISNULL(inv.disponible,0)) as totalDisponible,
            SUM(ISNULL(inv.existencias,0) * ISNULL(articulos.UltimoCosto,0)) as totalValorInventario
        ')
        ->first();
*/
$totales = (clone $query)
    ->selectRaw('
        COUNT(*) as total,
        SUM(ISNULL(articulos.CostoPromedio,0)) as totalCostoPromedio,
        SUM(ISNULL(articulos.UltimoCosto,0)) as totalUltimoCosto,
        SUM(ISNULL(inv.existencias,0)) as totalExistencias,
        SUM(ISNULL(inv.disponible,0)) as totalDisponible,
        -- 🔥 AQUI EL CAMBIO
        SUM(ISNULL(inv.existencias,0) * ISNULL(articulos.CostoPromedio,0)) as totalValorInventario
    ')
    ->first();
    // =========================
    // 📋 LISTADO
    // =========================
    $articulos = $query
        ->select(
            'articulos.*',
            'inv.existencias',
            'inv.disponible'
        )
        ->orderBy('articulos.Articulo')
        ->paginate(15)
        ->withQueryString();

    return view('articulos.index', [
        'articulos' => $articulos,
        'totalArticulos' => $totales->total ?? 0,
        'totalCostoPromedio' => $totales->totalCostoPromedio ?? 0,
        'totalUltimoCosto' => $totales->totalUltimoCosto ?? 0,
        'totalExistencias' => $totales->totalExistencias ?? 0,
        'totalDisponible' => $totales->totalDisponible ?? 0,
        'totalValorInventario' => $totales->totalValorInventario ?? 0,
    ]);
}
public function exportExcel(Request $request)
{
    return Excel::download(
        new ArticulosExport($request),
        'reporte_inventario.xlsx'
    );
}
public function exportPDF(Request $request)
{
    $query = Articulo::query()
        ->leftJoin('inventario_sucursales as inv', function ($join) {
            $join->on('articulos.Articulo', '=', 'inv.articulo')
                 ->on('articulos.Almacen', '=', 'inv.almacen');
        });

    // 🔍 FILTROS
    if ($request->filled('buscar')) {
        $buscar = $request->buscar;
        $query->where(function ($q) use ($buscar) {
            $q->where('articulos.Articulo', 'like', "%$buscar%")
              ->orWhere('articulos.Descripcion1', 'like', "%$buscar%");
        });
    }

    if ($request->filled('categoria')) {
        $query->where('articulos.Categoria', $request->categoria);
    }

    if ($request->filled('fabricante')) {
        $query->where('articulos.Fabricante', $request->fabricante);
    }

    if ($request->filled('almacen')) {
        $query->where('articulos.Almacen', $request->almacen);
    }

    // 🔥 SOLO CON EXISTENCIAS
    $query->whereRaw('ISNULL(inv.existencias,0) > 0');

    $articulos = $query->select(
        'articulos.Articulo',
        \DB::raw("CONCAT(articulos.Descripcion1,' ',articulos.Categoria,' ',articulos.Fabricante) as DescripcionCompleta"),
        'articulos.Categoria',
        'articulos.Fabricante',
        'inv.almacen',
        'inv.existencias',
        'inv.disponible',
        'articulos.Estatus',
        'articulos.CostoPromedio',
        \DB::raw('(inv.existencias * articulos.CostoPromedio) as Valor')
    )
    ->orderBy('articulos.Articulo')
    ->orderBy('inv.almacen')
    ->get();

    $pdf = Pdf::loadView('articulos.pdf', compact('articulos'));

    return $pdf->download('reporte_inventario.pdf');
}
}