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
            $table->index('nombre');
            $table->index('constructora');
            $table->index('direccion');
            $table->index('ciudad');
            $table->index('codigo_postal');
            $table->index('activa');
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
        // eliminamos los indices compuestos
        Schema::table('qr_privadas', function (Blueprint $table) {
            $table->dropIndex('index_nombre_constructora');
            $table->dropIndex('index_direccion_ciudad');
        });
        // eliminamos los indices
        Schema::table('qr_privadas', function (Blueprint $table) {
            $table->dropIndex(['nombre']);
            $table->dropIndex(['constructora']);
            $table->dropIndex(['direccion']);
            $table->dropIndex(['ciudad']);
            $table->dropIndex(['codigo_postal']);
            $table->dropIndex(['activa']);
        });
        // eliminamos las llaves unicas
        Schema::table('qr_privadas', function (Blueprint $table) {
            $table->dropUnique('unique_nombre_constructora');
            $table->dropUnique('unique_direccion_ciudad');
        });
        // eliminamos la tabla
        Schema::dropIfExists('privadas');
    }
};
