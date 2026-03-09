<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Sucursal;   // esta se queda si la usas



class InventarioSucursal extends Model
{
    protected $table = 'inventario_sucursales';

    protected $fillable = [
        'empresa',
        'articulo',
        'almacen',
        'existencias',
        'reservado',
        'remisionado',
        'disponible',
        'apartado',
        'fecha_sync'
    ];

    protected $casts = [
        'existencias'   => 'decimal:2',
        'reservado'     => 'decimal:2',
        'remisionado'   => 'decimal:2',
        'disponible'    => 'decimal:2',
        'apartado'      => 'decimal:2',
        'fecha_sync'    => 'datetime'
    ];

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'almacen', 'almacen_principal');
    }

    public function producto()
    {
        return $this->belongsTo(\App\Models\Producto::class, 'articulo', 'articulo');
    }
 
}