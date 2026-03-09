<?php

namespace App\Exports;

use App\Models\InventarioSucursal;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class InventarioExport implements FromView
{
    protected $inventarios;

    public function __construct($inventarios)
    {
        $this->inventarios = $inventarios;
    }

    public function view(): View
    {
        return view('exports.inventario_excel', [
            'inventarios' => $this->inventarios
        ]);
    }
}