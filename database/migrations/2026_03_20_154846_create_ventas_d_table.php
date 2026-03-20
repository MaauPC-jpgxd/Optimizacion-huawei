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
    Schema::create('ventas_d', function (Blueprint $table) {
      
        $table->integer('ID');
        $table->float('Renglon');
        $table->integer('RenglonSub');
        $table->string('Empresa', 5);
        $table->string('Mov', 20);
        $table->string('MovID', 20)->nullable();
        $table->string('Moneda', 10);

        $table->dateTime('FechaEmision')->nullable();
        $table->dateTime('FechaRequerida')->nullable();
        $table->dateTime('FechaSalida')->nullable();

        $table->string('HoraRequerida', 5)->nullable();
        $table->string('Prioridad', 10)->nullable();
        $table->string('Referencia', 50)->nullable();
        $table->string('Proyecto', 50)->nullable();
        $table->string('Concepto', 50)->nullable();
        $table->string('Estatus', 15)->nullable();

        $table->string('Cliente', 10);
        $table->integer('EnviarA')->nullable();

        $table->float('DescuentoGlobal')->nullable();
        $table->float('SobrePrecio')->nullable();

        $table->string('ServicioArticulo', 20)->nullable();
        $table->string('ServicioSerie', 50)->nullable();
        $table->dateTime('ServicioFecha')->nullable();
        $table->string('ServicioNumeroEconomico', 20)->nullable();

        $table->integer('Sucursal');
        $table->integer('SucursalOrigen');

        $table->string('Agente', 10)->nullable();
        $table->string('Almacen', 10)->nullable();
        $table->string('Articulo', 20);

        $table->string('SubCuenta', 50)->nullable();
        $table->string('Espacio', 10)->nullable();

        $table->float('Cantidad')->nullable();
        $table->float('CantidadReservada')->nullable();
        $table->float('CantidadOrdenada')->nullable();
        $table->float('CantidadPendiente')->nullable();

        $table->string('Unidad', 50)->nullable();

        $table->float('Factor')->nullable();
        $table->float('CantidadFactor')->nullable();
        $table->float('ReservadaFactor')->nullable();
        $table->float('OrdenadaFactor')->nullable();
        $table->float('PendienteFactor')->nullable();

        $table->float('CantidadInventario')->nullable();
        $table->float('Precio')->nullable();

        $table->char('DescuentoTipo', 1)->nullable();
        $table->float('DescuentoLinea')->nullable();

        $table->float('Impuesto1')->nullable();
        $table->float('Impuesto2')->nullable();
        $table->float('Impuesto3')->nullable();

        $table->float('Retencion1')->nullable();
        $table->float('Retencion2')->nullable();
        $table->float('Retencion3')->nullable();

        $table->string('DescripcionExtra', 100)->nullable();
        $table->string('Instruccion', 50)->nullable();
        $table->string('PoliticaPrecios', 255)->nullable();

        $table->string('PrecioMoneda', 10)->nullable();
        $table->float('PrecioTipoCambio')->nullable();

        $table->integer('Paquete')->nullable();
        $table->integer('UEN')->nullable();

        $table->string('CteNombre', 100)->nullable();
        $table->string('ArtDescripcion', 100)->nullable();

        $table->boolean('ArtSeProduce');
        $table->boolean('ArtSeCompra')->nullable();
        $table->boolean('Espacios')->nullable();

        $table->string('EspaciosNivel', 50)->nullable();
        $table->string('MovTipo', 20);

        $table->integer('Semana')->nullable();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventas_d');
    }
};
