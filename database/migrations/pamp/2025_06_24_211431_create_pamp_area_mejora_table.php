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
        Schema::create('pamp_area_mejora', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meta_id')->constrained('pamp_metas')->onDelete('cascade');
            $table->string('nombre')->unique();
            $table->text('description')->nullable();
            $table->string('icono')->nullable();
            $table->string('imagen')->nullable();
            $table->string('color')->default('#000000');
            $table->integer('dificultad')->default(100);
            $table->timestamps();
            $table->softDeletes();
        });
        // Generamos indices para mejorar el rendimiento de las consultas
        Schema::table('pamp_area_mejora', function (Blueprint $table) {
            $table->index('meta_id', 'index_pamp_am_meta_id');
            $table->index('nombre', 'index_pamp_am_nombre');
            $table->index('dificultad', 'index_pamp_am_dificultad');
        });
        // Generamos un indice compuesto
        Schema::table('pamp_area_mejora', function (Blueprint $table) {
            $table->index(['meta_id', 'nombre'], 'index_meta_nombre');
        });
        // Generamos llaves unicas
        Schema::table('pamp_area_mejora', function (Blueprint $table) {
            $table->unique(['meta_id', 'nombre'], 'unique_meta_nombre');
        });

        // creamos una tabla de relaciones entre pamp_area_mejora y pamp_guerreros
        Schema::create('pamp_area_mejora_guerreros', function (Blueprint $table) {
            $table->id();
            $table->foreignId('area_mejora_id')->constrained('pamp_area_mejora')->onDelete('cascade');
            $table->foreignId('guerrero_id')->constrained('pamp_guerreros')->onDelete('cascade');
            $table->integer('nivel')->default(1);
            $table->bigInteger('exp')->default(1);
            $table->boolean('activa')->default(true);
            $table->decimal('porcentaje')->default(0.0);
            $table->timestamps();
            $table->softDeletes();
        });
        // Generamos indices para mejorar el rendimiento de las consultas
        Schema::table('pamp_area_mejora_guerreros', function (Blueprint $table) {
            $table->index('area_mejora_id', 'index_pamp_amg_area_mejora_id');
            $table->index('guerrero_id', 'index_pamp_amg_guerrero_id');
            $table->index('nivel', 'index_pamp_amg_nivel');
        });
        // Generamos un indice compuesto
        Schema::table('pamp_area_mejora_guerreros', function (Blueprint $table) {
            $table->index(['area_mejora_id', 'guerrero_id'], 'index_area_mejora_guerrero');
        });
        // Generamos llaves unicas
        Schema::table('pamp_area_mejora_guerreros', function (Blueprint $table) {
            $table->unique(['area_mejora_id', 'guerrero_id'], 'unique_area_mejora_guerrero');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Eliminamos las llaves foraneas
        Schema::table('pamp_area_mejora_guerreros', function (Blueprint $table) {
            $table->dropForeign(['area_mejora_id']);
            $table->dropForeign(['guerrero_id']);
        });
        // Eliminamos las llaves unicas
        Schema::table('pamp_area_mejora_guerreros', function (Blueprint $table) {
            $table->dropUnique('unique_area_mejora_guerrero');
        });
        // Eliminamos los indices compuestos
        Schema::table('pamp_area_mejora_guerreros', function (Blueprint $table) {
            $table->dropIndex('index_area_mejora_guerrero');
        });
        // Eliminamos los indices
        Schema::table('pamp_area_mejora_guerreros', function (Blueprint $table) {
            $table->dropIndex('index_pamp_amg_area_mejora_id');
            $table->dropIndex('index_pamp_amg_guerrero_id');
            $table->dropIndex('index_pamp_amg_nivel');
        });
        // Eliminamos la tabla
        Schema::dropIfExists('pamp_area_mejora_guerreros');


        // Eliminamos las llaves foraneas
        Schema::table('pamp_area_mejora', function (Blueprint $table) {
            $table->dropForeign(['meta_id']);
        });
        // Eliminamos las llaves unicas
        Schema::table('pamp_area_mejora', function (Blueprint $table) {
            $table->dropUnique('unique_meta_nombre');
        });
        // Eliminamos los indices compuestos
        Schema::table('pamp_area_mejora', function (Blueprint $table) {
            $table->dropIndex('index_meta_nombre');
        });
        // Eliminamos los indices
        Schema::table('pamp_area_mejora', function (Blueprint $table) {
            $table->dropIndex('index_pamp_am_meta_id');
            $table->dropIndex('index_pamp_am_nombre');
            $table->dropIndex('index_pamp_am_dificultad');
        });
        // Eliminamos la tabla
        Schema::dropIfExists('pamp_area_mejora');
    }
};
