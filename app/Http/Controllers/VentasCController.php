<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VentasC; // 👈 tu modelo

class VentasCController extends Controller
{
    public function index(Request $request)
{
    $query = VentasC::query();

    // FILTRO TIPO
    if ($request->filled('tipo')) {
        $query->where('Mov', 'like', '%' . $request->tipo . '%');
    }

    // FILTRO MOVID
    if ($request->filled('movid')) {
        $query->where('MovID', 'like', '%' . $request->movid . '%');
    }

    // FECHAS
    if ($request->filled('fecha_inicio') && $request->filled('fecha_fin')) {
        $query->whereBetween('FechaEmision', [
            $request->fecha_inicio,
            $request->fecha_fin
        ]);
    }

    // IMPORTE
    if ($request->filled('importe_min')) {
        $query->where('Importe', '>=', $request->importe_min);
    }

    if ($request->filled('importe_max')) {
        $query->where('Importe', '<=', $request->importe_max);
    }

    // STATS
    $statsQuery = clone $query;

    $totalFacturas = $statsQuery->distinct('MovID')->count('MovID');
    $totalImporte = (clone $query)->sum('Importe');
    $totalGeneral = (clone $query)->sum('Total');

    // DATA
    $ventas = $query->orderBy('FechaEmision', 'desc')->paginate(15);

    return view('ventasc.index', compact(
        'ventas',
        'totalFacturas',
        'totalImporte',
        'totalGeneral'
    ));
}
}