<?php

namespace App\Exports;

use App\Models\VentaDetalle;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class VentasHuaweiExport implements FromView
{
    public function view(): View
    {
        $ventas = VentaDetalle::with('producto')
            ->whereHas('producto', function ($q) {
                $q->where('descripcion', 'like', '%huawei%');
            })
            ->whereIn('sucursal', [390, 400, 500, 600])
            ->orderBy('fecha', 'desc')
            ->get();

        return view('exports.ventas_huawei_excel', compact('ventas'));
    }
}