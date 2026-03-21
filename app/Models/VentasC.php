<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VentasC extends Model
{
    protected $table = 'ventas_c';

    protected $fillable = [
        'Empresa',
        'Mov',
        'MovID',
        'Moneda',
        'FechaEmision',
        'FechaRequerida',
        'Proyecto',
        'UEN',
        'Concepto',
        'Estatus',
        'Cliente',
        'EnviarA',
        'Agente',
        'Importe',
        'DescuentoGlobal',
        'SobrePrecio',
        'Referencia',
        'SubTotal',
        'Impuestos',
        'Total',
        'Saldo',
        'SaldoImpuestos',
        'MovTipo',
        'Sucursal',
        'SucursalOrigen',
        'Espacio',
        'Almacen',
        'AlmacenDestino',
        'ServicioSerie',
        'ServicioPlacas',
        'ServicioFecha',
        'ServicioArticulo',
        'ServicioNumeroEconomico'
    ];

    // 🔥 CASTS (CLAVE PARA NO TENER ERRORES)
    protected $casts = [
        'FechaEmision' => 'datetime',
        'FechaRequerida' => 'datetime',
        'ServicioFecha' => 'datetime',

        'Importe' => 'decimal:2',
        'SubTotal' => 'decimal:2',
        'Impuestos' => 'decimal:2',
        'Total' => 'decimal:2',
        'Saldo' => 'decimal:2',
        'SaldoImpuestos' => 'decimal:2',

        'DescuentoGlobal' => 'float',
        'SobrePrecio' => 'float',
    ];
}