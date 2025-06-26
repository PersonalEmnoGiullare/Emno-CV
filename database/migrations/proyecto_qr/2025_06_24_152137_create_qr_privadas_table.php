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
        // creamos la tabla qr_privadas
        Schema::create('qr_privadas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 200);
            $table->string('constructora', 200);
            $table->string('direccion', 200);
            $table->string('ciudad', 50);
            $table->string('codigo_postal', 10)->nullable();
            $table->boolean('activa')->default(true);
            $table->json('configuracion')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // generamos llaves unicas 
        Schema::table('qr_privadas', function (Blueprint $table) {
            $table->unique(['nombre', 'constructora'], 'unique_nombre_constructora');
            $table->unique(['direccion', 'ciudad'], 'unique_direccion_ciudad');
        });
        // generamos indices para mejorar el rendimiento de las consultas
        Schema::table('qr_privadas', function (Blueprint $table) {
            $table->index('nombre', 'index_qr_p_nombre');
            $table->index('constructora', 'index_qr_p_constructora');
            $table->index('direccion', 'index_qr_p_direccion');
            $table->index('ciudad', 'index_qr_p_ciudad');
            $table->index('codigo_postal', 'index_qr_p_codigo_postal');
            $table->index('activa', 'index_qr_p_activa');
            // indices compuestos 
            $table->index(['nombre', 'constructora'], 'index_nombre_constructora');
            $table->index(['direccion', 'ciudad'], 'index_direccion_ciudad');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // eliminamos las llaves unicas
        Schema::table('qr_privadas', function (Blueprint $table) {
            $table->dropUnique('unique_nombre_constructora');
            $table->dropUnique('unique_direccion_ciudad');
        });
        // eliminamos los indices compuestos
        Schema::table('qr_privadas', function (Blueprint $table) {
            $table->dropIndex('index_nombre_constructora');
            $table->dropIndex('index_direccion_ciudad');
        });
        // eliminamos los indices
        Schema::table('qr_privadas', function (Blueprint $table) {
            $table->dropIndex('index_qr_p_nombre');
            $table->dropIndex('index_qr_p_constructora');
            $table->dropIndex('index_qr_p_direccion');
            $table->dropIndex('index_qr_p_ciudad');
            $table->dropIndex('index_qr_p_codigo_postal');
            $table->dropIndex('index_qr_p_activa');
        });
        // eliminamos la tabla
        Schema::dropIfExists('qr_privadas');
    }
};
