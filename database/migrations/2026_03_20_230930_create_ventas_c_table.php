<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  public function up()
{
    Schema::create('ventas_c', function (Blueprint $table) {
        $table->id();

        $table->string('Empresa', 5);
        $table->string('Mov', 20);
        $table->string('MovID', 20)->nullable();
        $table->string('Moneda', 10);

        $table->dateTime('FechaEmision')->nullable();
        $table->dateTime('FechaRequerida')->nullable();

        $table->string('Proyecto', 50)->nullable();
        $table->integer('UEN')->nullable();
        $table->string('Concepto', 50)->nullable();
        $table->string('Estatus', 15)->nullable();

        $table->string('Cliente', 10);
        $table->integer('EnviarA')->nullable();
        $table->string('Agente', 10)->nullable();

        // 🔥 MONEY → decimal
        $table->decimal('Importe', 18, 2)->nullable();
        $table->float('DescuentoGlobal')->nullable();
        $table->float('SobrePrecio')->nullable();

        $table->string('Referencia', 50)->nullable();

        $table->decimal('SubTotal', 18, 2)->nullable();
        $table->decimal('Impuestos', 18, 2)->nullable();
        $table->decimal('Total', 18, 2)->nullable();
        $table->decimal('Saldo', 18, 2)->nullable();
        $table->decimal('SaldoImpuestos', 18, 2)->nullable();

        $table->string('MovTipo', 20);
        $table->integer('Sucursal');
        $table->integer('SucursalOrigen');

        $table->string('Espacio', 10)->nullable();
        $table->string('Almacen', 10);
        $table->string('AlmacenDestino', 10)->nullable();

        $table->string('ServicioSerie', 50)->nullable();
        $table->string('ServicioPlacas', 20)->nullable();
        $table->dateTime('ServicioFecha')->nullable();
        $table->string('ServicioArticulo', 20)->nullable();
        $table->string('ServicioNumeroEconomico', 20)->nullable();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventas_c');
    }
};
