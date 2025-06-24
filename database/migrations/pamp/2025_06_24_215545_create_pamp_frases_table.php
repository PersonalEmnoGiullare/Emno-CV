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
            $table->index('frase');
            $table->index('autor');
        });
        // Generamos tabla de relaciones entre pamp_frases y pamp_metas
        Schema::create('pamp_metas_frases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pamp_metas_id')->constrained('pamp_metas')->onDelete('cascade');
            $table->foreignId('pamp_frase_id')->constrained('pamp_frases')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
        // Generamos indices para mejorar el rendimiento de las consultas
        Schema::table('pamp_metas_frases', function (Blueprint $table) {
            $table->index('pamp_metas_id');
            $table->index('pamp_frase_id');
        });
        // Generamos un indice compuesto
        Schema::table('pamp_metas_frases', function (Blueprint $table) {
            $table->index(['pamp_metas_id', 'pamp_frase_id'], 'index_meta_frase');
        });
        // Generamos llaves unicas
        Schema::table('pamp_metas_frases', function (Blueprint $table) {
            $table->unique(['pamp_metas_id', 'pamp_frase_id'], 'unique_meta_frase');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Eliminamos los indices compuestos
        Schema::table('pamp_metas_frases', function (Blueprint $table) {
            $table->dropIndex('index_meta_frase');
        });
        // Eliminamos los indices
        Schema::table('pamp_metas_frases', function (Blueprint $table) {
            $table->dropIndex(['pamp_metas_id']);
            $table->dropIndex(['pamp_frase_id']);
        });
        // Eliminamos las llaves foraneas
        Schema::table('pamp_metas_frases', function (Blueprint $table) {
            $table->dropForeign(['pamp_metas_id']);
            $table->dropForeign(['pamp_frase_id']);
        });
        // Eliminamos la tabla de relaciones
        Schema::dropIfExists('pamp_metas_frases');
        // Eliminamos los indices
        Schema::table('pamp_frases', function (Blueprint $table) {
            $table->dropIndex(['frase']);
            $table->dropIndex(['autor']);
        });
        // Eliminamos las llaves unicas
        Schema::table('pamp_frases', function (Blueprint $table) {
            $table->dropUnique(['frase']);
        });
        // Eliminamos la tabla
        Schema::dropIfExists('pamp_frases');
    }
};
