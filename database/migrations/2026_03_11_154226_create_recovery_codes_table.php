<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up() {
        Schema::create('recovery_codes', function (Blueprint $table) {
            // Clave primaria
            $table->bigIncrements('id');
            
            // Relación con usuarios
            $table->bigInteger('user_id')->unsigned()->nullable(false);
            
            // Código de recuperación
            $table->string('code', 6)->nullable(false);
            
            // Estado activo (0 = inactivo, 1 = activo)
            $table->tinyInteger('is_active')->default(1)->nullable(false);
            
            // Fecha de expiración (1 hora después de crear)
            $table->datetime('expires_at')->default(DB::raw('DATEADD(HOUR, 1, GETDATE())'))->nullable(false);
            
            // Columnas de tiempo
            $table->datetime('created_at')->default(DB::raw('GETDATE()'))->nullable(false);
            $table->datetime('updated_at')->default(DB::raw('GETDATE()'))->nullable(false);

            // Clave foránea
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('CASCADE');
        });
    }

    public function down() {
        Schema::dropIfExists('recovery_codes');
    }
};