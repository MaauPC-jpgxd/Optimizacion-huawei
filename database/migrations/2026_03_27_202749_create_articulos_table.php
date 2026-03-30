<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articulos', function (Blueprint $table) {
            $table->id();

            $table->string('Articulo', 20);
            $table->string('Descripcion1', 100)->nullable();
            $table->string('Grupo', 50)->nullable();
            $table->string('Categoria', 50)->nullable();
            $table->string('Familia', 50)->nullable();
            $table->string('Fabricante', 50)->nullable();
            $table->string('ClaveFabricante', 50)->nullable();
            $table->string('MonedaCosto', 10);
            $table->string('Estatus', 15);
            $table->string('Codigo', 50)->nullable();
            $table->string('Empresa', 5)->nullable();

            $table->float('CostoPromedio')->nullable();
            $table->float('UltimoCosto')->nullable();
            $table->float('UltimoCostoSinGastos')->nullable();
            $table->float('CostoEstandar')->nullable();
            $table->float('CostoReposicion')->nullable();

            $table->char('Almacen', 10)->nullable();

            $table->timestamps();

            // 🔥 INDEX PRO
            $table->index('Articulo');
            $table->index('Categoria');
            $table->index('Fabricante');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articulos');
    }
};