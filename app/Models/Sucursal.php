<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sucursal extends Model
{
    use HasFactory;

    protected $table = 'sucursales';

    protected $fillable = [
        'clave',
        'nombre',
        'almacen_principal',
        'estado',
        'ciudad',
        'direccion',
        'codigo_postal',
        'activa',
    ];

    protected $casts = [
        'activa' => 'boolean',
    ];

    /**
     * Relación con inventario
     * Relaciona almacen (inventario) con almacen_principal (sucursal)
     */
    public function inventario()
    {
        return $this->hasMany(Articulo::class, 'almacen', 'almacen_principal');
    }
}