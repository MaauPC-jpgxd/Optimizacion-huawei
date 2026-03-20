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
            //quien vendio aqui se agrega el agente
            $table->string('agente')->nullable()->after('cliente');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ventas_cabecera', function (Blueprint $table) {
            //
            $table->dropColumn('agente');
        });
    }
};
