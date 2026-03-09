<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VentaDetalle extends Model
{
    use HasFactory;

    protected $table = 'ventas_detalle';

    protected $fillable = [
        'codigo',
        'articulo',
        'sucursal',
        'cantidad_vendida',
        'monto_vendido',
        'fecha_contable'
    ];

    protected $casts = [
        'cantidad_vendida' => 'decimal:2',
        'monto_vendido' => 'decimal:2',
        'fecha_contable' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIONES (si tienes tabla productos)
    |--------------------------------------------------------------------------
    */

   public function producto()
{
    return $this->belongsTo(Producto::class, 'articulo', 'articulo');
}
    /*
    |--------------------------------------------------------------------------
    | SCOPES ÚTILES
    |--------------------------------------------------------------------------
    */

    // Solo por sucursal
    public function scopeSucursal($query, $sucursal)
    {
        return $query->where('sucursal', $sucursal);
    }

    // Solo por producto
    public function scopeProductoCodigo($query, $codigo)
    {
        return $query->where('codigo', $codigo);
    }

    
}