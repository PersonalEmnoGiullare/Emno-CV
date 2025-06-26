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
        Schema::create('pamp_metas', function (Blueprint $table) {
            $table->id();
            $table->string('titulo')->unique();
            $table->text('descripcion')->nullable();
            $table->string('imagen')->nullable();
            $table->boolean('activa')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
        // Generamos indices para mejorar el rendimiento de las consultas
        Schema::table('pamp_metas', function (Blueprint $table) {
            $table->index('titulo', 'index_pamp_m_titulo');
        });
        // Generamos un indice compuesto
        Schema::table('pamp_metas', function (Blueprint $table) {
            $table->index(['titulo', 'created_at'], 'index_titulo_created_at');
        });
        // creamos una tabla de relaciones entre pamp_metas y pamp_guerreros
        Schema::create('pamp_metas_guerreros', function (Blueprint $table) {
            $table->id();
            $table->foreignId('metas_id')->constrained('pamp_metas')->onDelete('cascade');
            $table->foreignId('guerrero_id')->constrained('pamp_guerreros')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
        // Generamos indices para mejorar el rendimiento de las consultas
        Schema::table('pamp_metas_guerreros', function (Blueprint $table) {
            $table->index('metas_id', 'index_pamp_mg_metas_id');
            $table->index('guerrero_id', 'index_pamp_mg_guerrero_id');
        });
        // Generamos un indice compuesto
        Schema::table('pamp_metas_guerreros', function (Blueprint $table) {
            $table->index(['metas_id', 'guerrero_id'], 'index_meta_guerrero');
        });
        // Generamos llaves unicas
        Schema::table('pamp_metas_guerreros', function (Blueprint $table) {
            $table->unique(['metas_id', 'guerrero_id'], 'unique_meta_guerrero');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Eliminamos las llaves foraneas
        Schema::table('pamp_metas_guerreros', function (Blueprint $table) {
            $table->dropForeign(['metas_id']);
            $table->dropForeign(['guerrero_id']);
        });
        // Eliminamos las llaves unicas
        Schema::table('pamp_metas_guerreros', function (Blueprint $table) {
            $table->dropUnique('unique_meta_guerrero');
        });
        // Eliminamos los indices compuestos
        Schema::table('pamp_metas_guerreros', function (Blueprint $table) {
            $table->dropIndex('index_meta_guerrero');
        });
        // Eliminamos los indices
        Schema::table('pamp_metas_guerreros', function (Blueprint $table) {
            $table->dropIndex('index_pamp_mg_metas_id');
            $table->dropIndex('index_pamp_mg_guerrero_id');
        });
        // Eliminamos la tabla
        Schema::dropIfExists('pamp_metas_guerreros');


        // Eliminamos las llaves unicas
        Schema::table('pamp_metas', function (Blueprint $table) {
            $table->dropUnique(['titulo']);
        });
        // Eliminamos los indices compuestos
        Schema::table('pamp_metas', function (Blueprint $table) {
            $table->dropIndex('index_titulo_created_at');
        });
        // Eliminamos los indices
        Schema::table('pamp_metas', function (Blueprint $table) {
            $table->dropIndex('index_pamp_m_titulo');
        });
        // Eliminamos la tabla
        Schema::dropIfExists('pamp_metas');
    }
};
