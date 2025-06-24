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
            $table->index('meta_id');
            $table->index('nombre');
            $table->index('dificultad');
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
            $table->foreignId('pamp_area_mejora_id')->constrained('pamp_area_mejora')->onDelete('cascade');
            $table->foreignId('pamp_guerrero_id')->constrained('pamp_guerreros')->onDelete('cascade');
            $table->integer('nivel')->default(1);
            $table->bigInteger('exp')->default(1);
            $table->boolean('activa')->default(true);
            $table->decimal('porcentaje')->default(0.0);
            $table->timestamps();
            $table->softDeletes();
        });
        // Generamos indices para mejorar el rendimiento de las consultas
        Schema::table('pamp_area_mejora_guerreros', function (Blueprint $table) {
            $table->index('pamp_area_mejora_id');
            $table->index('pamp_guerrero_id');
            $table->index('nivel');
        });
        // Generamos un indice compuesto
        Schema::table('pamp_area_mejora_guerreros', function (Blueprint $table) {
            $table->index(['pamp_area_mejora_id', 'pamp_guerrero_id'], 'index_area_mejora_guerrero');
        });
        // Generamos llaves unicas
        Schema::table('pamp_area_mejora_guerreros', function (Blueprint $table) {
            $table->unique(['pamp_area_mejora_id', 'pamp_guerrero_id'], 'unique_area_mejora_guerrero');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Eliminamos los indices compuestos
        Schema::table('pamp_area_mejora_guerreros', function (Blueprint $table) {
            $table->dropIndex('index_area_mejora_guerrero');
        });
        // Eliminamos los indices
        Schema::table('pamp_area_mejora_guerreros', function (Blueprint $table) {
            $table->dropIndex(['pamp_area_mejora_id']);
            $table->dropIndex(['pamp_guerrero_id']);
            $table->dropIndex(['nivel']);
        });
        // Eliminamos las llaves unicas
        Schema::table('pamp_area_mejora_guerreros', function (Blueprint $table) {
            $table->dropUnique('unique_area_mejora_guerrero');
        });
        // Eliminamos las llaves foraneas
        Schema::table('pamp_area_mejora_guerreros', function (Blueprint $table) {
            $table->dropForeign(['pamp_area_mejora_id']);
            $table->dropForeign(['pamp_guerrero_id']);
        });
        // Eliminamos la tabla
        Schema::dropIfExists('pamp_area_mejora_guerreros');

        // Eliminamos los indices compuestos
        Schema::table('pamp_area_mejora', function (Blueprint $table) {
            $table->dropIndex('index_meta_nombre');
        });
        // Eliminamos los indices
        Schema::table('pamp_area_mejora', function (Blueprint $table) {
            $table->dropIndex(['meta_id']);
            $table->dropIndex(['nombre']);
            $table->dropIndex(['dificultad']);
        });
        // Eliminamos las llaves unicas
        Schema::table('pamp_area_mejora', function (Blueprint $table) {
            $table->dropUnique('unique_meta_nombre');
        });
        // Eliminamos las llaves foraneas
        Schema::table('pamp_area_mejora', function (Blueprint $table) {
            $table->dropForeign(['meta_id']);
        });
        // Eliminamos la tabla
        Schema::dropIfExists('pamp_area_mejora');
    }
};
