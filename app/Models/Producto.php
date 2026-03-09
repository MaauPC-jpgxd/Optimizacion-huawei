<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'articulo',
        'descripcion',
        'nombre_corto',
        'grupo',
        'estatus'
    ];

    /*public function inventarios()
    {
        return $this->hasMany(Articulo::class, 'articulo', 'articulo');
    }*/
}