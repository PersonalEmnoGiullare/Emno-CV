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
        Schema::create('pamp_frases', function (Blueprint $table) {
            $table->id();
            $table->text('frase')->unique();
            $table->string('autor')->nullable();
            $table->string('imagen')->nullable();
            $table->boolean('activa')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
        // Generamos indices para mejorar el rendimiento de las consultas
        Schema::table('pamp_frases', function (Blueprint $table) {
            $table->index('frase', 'index_pamp_f_frase');
            $table->index('autor', 'index_pamp_f_autor');
        });
        // Generamos tabla de relaciones entre pamp_frases y pamp_metas
        Schema::create('pamp_metas_frases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('metas_id')->constrained('pamp_metas')->onDelete('cascade');
            $table->foreignId('frase_id')->constrained('pamp_frases')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
        // Generamos indices para mejorar el rendimiento de las consultas
        Schema::table('pamp_metas_frases', function (Blueprint $table) {
            $table->index('metas_id', 'index_pamp_mf_metas_id');
            $table->index('frase_id', 'index_pamp_mf_frase_id');
        });
        // Generamos un indice compuesto
        Schema::table('pamp_metas_frases', function (Blueprint $table) {
            $table->index(['metas_id', 'frase_id'], 'index_meta_frase');
        });
        // Generamos llaves unicas
        Schema::table('pamp_metas_frases', function (Blueprint $table) {
            $table->unique(['metas_id', 'frase_id'], 'unique_meta_frase');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Eliminamos las llaves foraneas
        Schema::table('pamp_metas_frases', function (Blueprint $table) {
            $table->dropForeign(['metas_id']);
            $table->dropForeign(['frase_id']);
        });
        // Eliminamos las llaves unicas
        Schema::table('pamp_frases', function (Blueprint $table) {
            $table->dropUnique(['frase']);
        });
        // Eliminamos los indices compuestos
        Schema::table('pamp_metas_frases', function (Blueprint $table) {
            $table->dropIndex('index_meta_frase');
        });
        // Eliminamos los indices
        Schema::table('pamp_metas_frases', function (Blueprint $table) {
            $table->dropIndex('index_pamp_mf_metas_id');
            $table->dropIndex('index_pamp_mf_frase_id');
        });
        // Eliminamos la tabla de relaciones
        Schema::dropIfExists('pamp_metas_frases');


        // Eliminamos los indices
        Schema::table('pamp_frases', function (Blueprint $table) {
            $table->dropIndex('index_pamp_f_frase');
            $table->dropIndex('index_pamp_f_autor');
        });
        // Eliminamos la tabla
        Schema::dropIfExists('pamp_frases');
    }
};
