<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
    protected $table = 'articulos';

    protected $fillable = [
        'Articulo',
        'Descripcion1',
        'Grupo',
        'Categoria',
        'Familia',
        'Fabricante',
        'ClaveFabricante',
        'MonedaCosto',
        'Estatus',
        'Codigo',
        'Empresa',
        'CostoPromedio',
        'UltimoCosto',
        'UltimoCostoSinGastos',
        'CostoEstandar',
        'CostoReposicion',
        'Almacen'
    ];
}