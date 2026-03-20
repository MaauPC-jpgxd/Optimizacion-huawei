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
        Schema::table('ventas_cabecera', function (Blueprint $table) {
            //cambiar a int jajaja xd
            $table->integer('sucursal')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ventas_cabecera', function (Blueprint $table) {
            //borrar string xd
            $table->string('sucursal')->change();
        });
    }
};
