<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

class VentasExport implements FromCollection
{
    protected $ventas;

    public function __construct($ventas)
    {
        $this->ventas = $ventas;
    }

    public function collection()
    {
        return $this->ventas->map(function ($v) {
            return [
                'Folio' => $v->MovID,
                'Tipo' => $v->Mov,
                'Sucursal' => $v->Sucursal,
                'Fecha' => $v->FechaEmision,
                'Importe' => $v->Importe,
                'Total' => $v->Total,
            ];
        });
    }
}