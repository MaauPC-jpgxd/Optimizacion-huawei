<?php
namespace App\Exports;
use App\Models\VentasCabecera;
use Maatwebsite\Excel\Concerns\FromCollection;

class VentaCabeceraExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $request;
    public function collection()
    {
        //1.- se inicia query base
     $query=VentasCabecera::query();
     //filtro de busqueda
     if ($this->request->filled('sucursal')){
        $query->where('sucursal',$this->requesr->sucursal);
     }
     //fecha filtro
     if ($this->request->filled('fecha_inicio') && $this->request->filled('fecha_fin')){
        $query->whereBetween('fecha_emision',[
            $this->request->fecha_inicio,
            $this->request->fecha_fin
        ]);
     }
     //ordenar y procesar
     return $query->orderBy('fecha_emision','desc')->get();
    }
}
