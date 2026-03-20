<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class VentasD extends Model
{
    // Nombre de la tabla
    protected $table = 'ventas_d';
    // Llave primaria (si no es 'id' se especifica)
    protected $primaryKey = 'ID';
    // Si la PK no es auto-incrementable
    public $incrementing = false;
    // Tipo de la PK
    protected $keyType = 'int';
    // Timestamps
    public $timestamps = true; // created_at y updated_at
    // Campos que se pueden asignar en masa
    protected $fillable = [
        'ID','Renglon','RenglonSub','Empresa','Mov','MovID','Moneda',
        'FechaEmision','FechaRequerida','FechaSalida','HoraRequerida',
        'Prioridad','Referencia','Proyecto','Concepto','Estatus',
        'Cliente','EnviarA','DescuentoGlobal','SobrePrecio',
        'ServicioArticulo','ServicioSerie','ServicioFecha','ServicioNumeroEconomico',
        'Sucursal','SucursalOrigen','Agente','Almacen','Articulo',
        'SubCuenta','Espacio','Cantidad','CantidadReservada','CantidadOrdenada','CantidadPendiente',
        'Unidad','Factor','CantidadFactor','ReservadaFactor','OrdenadaFactor','PendienteFactor',
        'CantidadInventario','Precio','DescuentoTipo','DescuentoLinea',
        'Impuesto1','Impuesto2','Impuesto3','Retencion1','Retencion2','Retencion3',
        'DescripcionExtra','Instruccion','PoliticaPrecios',
        'PrecioMoneda','PrecioTipoCambio','Paquete','UEN',
        'CteNombre','ArtDescripcion','ArtSeProduce','ArtSeCompra',
        'Espacios','EspaciosNivel','MovTipo','Semana'
    ];
    // Cast de tipos
    protected $casts = [
        'FechaEmision' => 'datetime',
        'FechaRequerida' => 'datetime',
        'FechaSalida' => 'datetime',
        'ServicioFecha' => 'datetime',
        'Cantidad' => 'float',
        'CantidadReservada' => 'float',
        'CantidadOrdenada' => 'float',
        'CantidadPendiente' => 'float',
        'Factor' => 'float',
        'CantidadFactor' => 'float',
        'ReservadaFactor' => 'float',
        'OrdenadaFactor' => 'float',
        'PendienteFactor' => 'float',
        'CantidadInventario' => 'float',
        'Precio' => 'decimal:2',
        'DescuentoGlobal' => 'float',
        'SobrePrecio' => 'float',
        'DescuentoLinea' => 'float',
        'Impuesto1' => 'float',
        'Impuesto2' => 'float',
        'Impuesto3' => 'float',
        'Retencion1' => 'float',
        'Retencion2' => 'float',
        'Retencion3' => 'float',
        'EnviarA' => 'int',
        'Paquete' => 'int',
        'UEN' => 'int',
        'Sucursal' => 'int',
        'SucursalOrigen' => 'int',
        'RenglonSub' => 'int',
        'Renglon' => 'float',
        'ArtSeProduce' => 'boolean',
        'ArtSeCompra' => 'boolean',
        'Espacios' => 'boolean',
        'Semana' => 'int',
    ];
}