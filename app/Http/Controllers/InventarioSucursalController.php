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
    $sucursalId = $request->sucursal;

    $query = InventarioSucursal::with('producto')
        ->whereHas('producto', function ($q) {
            $q->where('descripcion', 'like', '%HUAWEI%');
        });

    if ($sucursalId) {
        $query->whereRaw('FLOOR(almacen) = ?', [$sucursalId]);
    }

    $inventarios = $query
        ->orderByDesc('existencias')
        ->get();

    $sucursales = Sucursal::all();

    return view('inventario.index', compact(
        'inventarios',
        'sucursales',
        'sucursalId'
    ));
}
    // Solo root puede acceder a estas acciones (editar, etc.)
    public function edit($id)
    {
        $this->authorizeRoot();

        $item = InventarioSucursal::findOrFail($id);
        return view('inventario.edit', compact('item'));
    }

    // ... más adelante puedes agregar update, movimientos, etc.

    private function authorizeRoot()
    {
        if (auth()->user()->role !== 'root') {
            abort(403, 'Solo el usuario root puede editar inventario.');
        }
    }
    private function filtrarInventario($request)
{
    $query = InventarioSucursal::with('producto');

    if ($request->filled('sucursal')) {
        $query->whereRaw('FLOOR(almacen) = ?', [$request->sucursal]);
    }

    return $query->get();
}
public function exportExcel(Request $request)
{
    $sucursalId = $request->sucursal;

    $query = InventarioSucursal::with('producto')
        ->whereHas('producto', function ($q) {
            $q->where('descripcion', 'like', '%HUAWEI%');
        });

    if ($sucursalId) {
        $query->where('sucursal', $sucursalId);
    }

    $inventarios = $query->get();

    return Excel::download(new InventarioExport($inventarios), 'inventario_huawei.xlsx');
}
public function exportPdf(Request $request)
{
    $sucursalId = $request->sucursal;

    $query = InventarioSucursal::with('producto')
        ->whereHas('producto', function ($q) {
            $q->where('descripcion', 'like', '%HUAWEI%');
        });

    if ($sucursalId) {
        $query->where('sucursal', $sucursalId);
    }

    $inventarios = $query->get();
   $pdf = PDF::loadView('exports.inventario_pdf', compact('inventarios'));
    return $pdf->download('inventario_huawei.pdf');
}
}