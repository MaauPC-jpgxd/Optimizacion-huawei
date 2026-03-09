<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sucursales', function (Blueprint $table) {
            $table->id();
            $table->string('clave')->unique(); // Ej: 390
            $table->string('nombre');
            $table->string('almacen_principal'); // Ej: 390.1

            $table->string('estado')->nullable();
            $table->string('ciudad')->nullable();
            $table->string('direccion')->nullable();
            $table->string('codigo_postal', 10)->nullable();

            $table->boolean('activa')->default(true);

            $table->timestamps(); // created_at y updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sucursales');
    }
};