<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('articulo')->unique(); // clave del ERP
            $table->string('descripcion')->nullable();
            $table->string('nombre_corto')->nullable();
            $table->string('grupo')->nullable();
            $table->string('estatus')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};