<?php

namespace App\Exports;

use App\Models\VentasD;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;

class VentasDExport implements FromCollection
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = VentasD::query();

        // 🔍 FILTROS (MISMO QUE CONTROLLER)
        if ($this->request->filled('sucursal')) {
            $query->where('Sucursal', $this->request->sucursal);
        }

        if ($this->request->filled('articulo')) {
            $query->where('Articulo', 'like', '%' . $this->request->articulo . '%');
        }

        if ($this->request->filled('cliente')) {
            $query->where('Cliente', 'like', '%' . $this->request->cliente . '%');
        }

        if ($this->request->filled('estatus')) {
            $query->where('Estatus', $this->request->estatus);
        }

        if ($this->request->filled('tipo')) {
            $query->where('Mov', 'like', '%' . $this->request->tipo . '%');
        }

        if ($this->request->filled('fecha_inicio') && $this->request->filled('fecha_fin')) {
            $query->whereBetween('FechaEmision', [
                $this->request->fecha_inicio,
                $this->request->fecha_fin
            ]);
        }

        return $query->get();
    }
}