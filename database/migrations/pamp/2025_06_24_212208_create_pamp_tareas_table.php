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
        Schema::create('pamp_tareas', function (Blueprint $table) {
            $table->id();
            $table->string('titulo')->unique();
            $table->text('descripcion')->nullable();
            $table->string('icono')->nullable();
            $table->string('imagen')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        // Generamos indices para mejorar el rendimiento de las consultas
        Schema::table('pamp_tareas', function (Blueprint $table) {
            $table->index('titulo', 'index_pamp_t_titulo');
        });
        // generamos una tabla de relaciones entre pamp_tareas y pamp_area_mejora
        Schema::create('pamp_area_mejora_tareas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('area_mejora_id')->constrained('pamp_area_mejora')->onDelete('cascade');
            $table->foreignId('tarea_id')->constrained('pamp_tareas')->onDelete('cascade');
            $table->integer('exp_otorga')->default(0);
            $table->string('tipo_exponencial')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
        // Generamos indices para mejorar el rendimiento de las consultas
        Schema::table('pamp_area_mejora_tareas', function (Blueprint $table) {
            $table->index('area_mejora_id', 'index_pamp_amt_area_mejora_id');
            $table->index('tarea_id', 'index_pamp_amt_tarea_id');
            $table->index('exp_otorga', 'index_pamp_amt_exp_otorga');
        });
        // Generamos un indice compuesto
        Schema::table('pamp_area_mejora_tareas', function (Blueprint $table) {
            $table->index(['area_mejora_id', 'tarea_id'], 'index_area_mejora_titulo');
        });
        // Generamos llaves unicas
        Schema::table('pamp_area_mejora_tareas', function (Blueprint $table) {
            $table->unique(['area_mejora_id', 'tarea_id'], 'unique_area_mejora_titulo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Eliminamos las llaves foraneas
        Schema::table('pamp_area_mejora_tareas', function (Blueprint $table) {
            $table->dropForeign(['area_mejora_id']);
            $table->dropForeign(['tarea_id']);
        });
        // Eliminamos las llaves unicas
        Schema::table('pamp_area_mejora_tareas', function (Blueprint $table) {
            $table->dropUnique('unique_area_mejora_titulo');
        });
        // Eliminamos los indices compuestos
        Schema::table('pamp_area_mejora_tareas', function (Blueprint $table) {
            $table->dropIndex('index_area_mejora_titulo');
        });
        // Eliminamos los indices
        Schema::table('pamp_area_mejora_tareas', function (Blueprint $table) {
            $table->dropIndex('index_pamp_amt_area_mejora_id');
            $table->dropIndex('index_pamp_amt_tarea_id');
            $table->dropIndex('index_pamp_amt_exp_otorga');
        });
        // Eliminamos la tabla
        Schema::dropIfExists('pamp_area_mejora_tareas');


        // Eliminamos las llaves unicas
        Schema::table('pamp_tareas', function (Blueprint $table) {
            $table->dropUnique(['titulo']);
        });
        // Eliminamos los indices
        Schema::table('pamp_tareas', function (Blueprint $table) {
            $table->dropIndex('index_pamp_t_titulo');
        });
        // Eliminamos la tabla de tareas
        Schema::dropIfExists('pamp_tareas');
    }
};
