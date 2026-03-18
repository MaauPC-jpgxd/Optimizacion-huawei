<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VentasCabecera extends Model
{
    protected $table='ventas_cabecera';
    protected $fillable=[
        'empresa',
        'mov',
        'mov_id',
        'fecha_emision',
        'ultimo_cambio',
        'cliente',
        'sucursal',
        'almacen',
        'estatus',
        'importe',
        'impuestos',
        'total',
        'fecha_sync'
            ];
    protected $casts=[
        'fecha_emision'=> 'date',
        'ultimo_cambio'=>'datetime',
        'fecha_sync'=>'datetime',
        'importe'=>'decimal',
        'impuestos'=>'decimal',
        'total'=>'decimal',
    ];
    public function detalles (){
        return $this->hasMany(VentaDetalle::class,'ventas_cabecera');
    }
}
