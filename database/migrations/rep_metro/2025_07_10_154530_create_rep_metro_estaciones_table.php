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
        Schema::create('rep_metro_estaciones', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 60)->unique();
            $table->string('ubicacion', 255)->nullable();
            $table->boolean('activa')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
        // Generamos indices para mejorar el rendimiento de las consultas
        Schema::table('rep_metro_estaciones', function (Blueprint $table) {
            $table->index('nombre', 'index_est_nombre');
            $table->index('ubicacion', 'index_est_ubicacion');
            $table->index('activa', 'index_est_activa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Eliminamos los indices 
        Schema::table('rep_metro_estaciones', function (Blueprint $table) {
            $table->dropIndex('index_est_nombre');
            $table->dropIndex('index_est_ubicacion');
            $table->dropIndex('index_est_activa');
        });
        // Eliminamos la tabla estaciones
        Schema::dropIfExists('rep_metro_estaciones');
    }
};
