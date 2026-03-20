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
        Schema::create('ventas_cabecera', function (Blueprint $table) {
            $table->id();
            //erp identificación 
             // 🔹 Identificación ERP
                $table->string('empresa')->nullable();
                $table->string('mov')->nullable(); // tipo documento (FACTURA, REMISION, etc)
                $table->string('mov_id')->nullable();
                // 🔹 Fechas
                $table->date('fecha_emision')->nullable();
                $table->timestamp('ultimo_cambio')->nullable();
                // 🔹 Cliente y sucursal
                $table->string('cliente')->nullable();
                $table->string('sucursal')->nullable();
                // 🔹 Almacén
                $table->string('almacen')->nullable();
                // 🔹 Estado
                $table->string('estatus')->nullable();
                // 🔹 Totales
                $table->decimal('importe', 12, 2)->default(0);
                $table->decimal('impuestos', 12, 2)->default(0);
                $table->decimal('total', 12, 2)->default(0);
               
                // 🔹 Control
                $table->timestamp('fecha_sync')->nullable(); 
                    });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventas_cabecera');
    }
};

