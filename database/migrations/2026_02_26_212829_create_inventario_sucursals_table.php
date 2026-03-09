<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inventario_sucursales', function (Blueprint $table) {
        $table->id();

        $table->string('empresa', 100);
        $table->string('articulo', 100);
        $table->string('almacen', 100);

        $table->decimal('existencias', 18, 2)->default(0);
        $table->decimal('reservado', 18, 2)->default(0);
        $table->decimal('remisionado', 18, 2)->default(0);
        $table->decimal('disponible', 18, 2)->default(0);
        $table->decimal('apartado', 18, 2)->default(0);

        $table->timestamp('fecha_sync')->nullable();

        $table->timestamps();

        $table->unique(['empresa', 'articulo', 'almacen']);
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventario_sucursals');
    }
};
