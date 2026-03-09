<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ventas_detalle', function (Blueprint $table) {
            $table->id();

            $table->string('articulo')->index();
            $table->string('sucursal')->nullable()->index();

            $table->decimal('cantidad', 18, 4);
            $table->decimal('precio', 18, 4);
            $table->decimal('descuento', 18, 4)->default(0);

            $table->decimal('monto', 18, 4); // cantidad * precio - descuento

            $table->date('fecha')->index();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ventas_detalle');
    }
};