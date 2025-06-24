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
        Schema::create('pamp_logros', function (Blueprint $table) {
            $table->id();
            $table->string('titulo')->unique();
            $table->text('descipcion')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        // Generamos indices para mejorar el rendimiento de las consultas
        Schema::table('pamp_logros', function (Blueprint $table) {
            $table->index('titulo');
        });
        // Generamos una tabla de relaciones entre pamp_logros y pamp_metass
        Schema::create('pamp_metas_logros', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pamp_metas_id')->constrained('pamp_metas')->onDelete('cascade');
            $table->foreignId('pamp_logro_id')->constrained('pamp_logros')->onDelete('cascade');
            $table->integer('nvl_necesario')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
        // Generamos indices para mejorar el rendimiento de las consultas
        Schema::table('pamp_metas_logros', function (Blueprint $table) {
            $table->index('pamp_metas_id');
            $table->index('pamp_logro_id');
            $table->index('nvl_necesario');
        });
        // Generamos un indice compuesto
        Schema::table('pamp_metas_logros', function (Blueprint $table) {
            $table->index(['pamp_metas_id', 'pamp_logro_id'], 'index_meta_logro');
        });
        // Generamos llaves unicas
        Schema::table('pamp_metas_logros', function (Blueprint $table) {
            $table->unique(['pamp_metas_id', 'pamp_logro_id'], 'unique_meta_logro');
        });
        // Generamos una tabla de relaciones entre pamp_logros y pamp_area_mejora
        Schema::create('pamp_area_mejora_logros', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pamp_area_mejora_id')->constrained('pamp_area_mejora')->onDelete('cascade');
            $table->foreignId('pamp_logro_id')->constrained('pamp_logros')->onDelete('cascade');
            $table->integer('nvl_necesario')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
        // Generamos indices para mejorar el rendimiento de las consultas
        Schema::table('pamp_area_mejora_logros', function (Blueprint $table) {
            $table->index('pamp_area_mejora_id');
            $table->index('pamp_logro_id');
            $table->index('nvl_necesario');
        });
        // Generamos un indice compuesto
        Schema::table('pamp_area_mejora_logros', function (Blueprint $table) {
            $table->index(['pamp_area_mejora_id', 'pamp_logro_id'], 'index_area_mejora_logro');
        });
        // Generamos llaves unicas
        Schema::table('pamp_area_mejora_logros', function (Blueprint $table) {
            $table->unique(['pamp_area_mejora_id', 'pamp_logro_id'], 'unique_area_mejora_logro');
        });
        // Generamos una tabla de relaciones entre pamp_logros y pamp_guerreros
        Schema::create('pamp_guerreros_logros', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pamp_logro_id')->constrained('pamp_logros')->onDelete('cascade');
            $table->foreignId('pamp_guerrero_id')->constrained('pamp_guerreros')->onDelete('cascade');
            $table->boolean('activa')->default(true);
            $table->decimal('porcentaje')->default(0.0);
            $table->timestamps();
            $table->softDeletes();
        });
        // Generamos indices para mejorar el rendimiento de las consultas
        Schema::table('pamp_guerreros_logros', function (Blueprint $table) {
            $table->index('pamp_logro_id');
            $table->index('pamp_guerrero_id');
        });
        // Generamos un indice compuesto
        Schema::table('pamp_guerreros_logros', function (Blueprint $table) {
            $table->index(['pamp_logro_id', 'pamp_guerrero_id'], 'index_logro_guerrero');
        });
        // Generamos llaves unicas
        Schema::table('pamp_guerreros_logros', function (Blueprint $table) {
            $table->unique(['pamp_logro_id', 'pamp_guerrero_id'], 'unique_logro_guerrero');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Eliminamos los indices compuestos
        Schema::table('pamp_guerreros_logros', function (Blueprint $table) {
            $table->dropIndex('index_logro_guerrero');
        });
        // Eliminamos los indices
        Schema::table('pamp_guerreros_logros', function (Blueprint $table) {
            $table->dropIndex(['pamp_logro_id']);
            $table->dropIndex(['pamp_guerrero_id']);
        });
        // Eliminamos las llaves unicas
        Schema::table('pamp_guerreros_logros', function (Blueprint $table) {
            $table->dropUnique('unique_logro_guerrero');
        });
        // Eliminamos las llaves foraneas
        Schema::table('pamp_guerreros_logros', function (Blueprint $table) {
            $table->dropForeign(['pamp_logro_id']);
            $table->dropForeign(['pamp_guerrero_id']);
        });
        // Eliminamos la tabla
        Schema::dropIfExists('pamp_guerreros_logros');
        // Eliminamos los indices compuestos
        Schema::table('pamp_area_mejora_logros', function (Blueprint $table) {
            $table->dropIndex('index_area_mejora_logro');
        });
        // Eliminamos los indices
        Schema::table('pamp_area_mejora_logros', function (Blueprint $table) {
            $table->dropIndex(['pamp_area_mejora_id']);
            $table->dropIndex(['pamp_logro_id']);
            $table->dropIndex(['nvl_necesario']);
        });
        // Eliminamos las llaves unicas
        Schema::table('pamp_area_mejora_logros', function (Blueprint $table) {
            $table->dropUnique('unique_area_mejora_logro');
        });
        // Eliminamos las llaves foraneas
        Schema::table('pamp_area_mejora_logros', function (Blueprint $table) {
            $table->dropForeign(['pamp_area_mejora_id']);
            $table->dropForeign(['pamp_logro_id']);
        });
        // Eliminamos la tabla
        Schema::dropIfExists('pamp_area_mejora_logros');
        // Eliminamos los indices compuestos
        Schema::table('pamp_metas_logros', function (Blueprint $table) {
            $table->dropIndex('index_meta_logro');
        });
        // Eliminamos los indices
        Schema::table('pamp_metas_logros', function (Blueprint $table) {
            $table->dropIndex(['pamp_metas_id']);
            $table->dropIndex(['pamp_logro_id']);
            $table->dropIndex(['nvl_necesario']);
        });
        // Eliminamos las llaves unicas
        Schema::table('pamp_metas_logros', function (Blueprint $table) {
            $table->dropUnique('unique_meta_logro');
        });
        // Eliminamos las llaves foraneas
        Schema::table('pamp_metas_logros', function (Blueprint $table) {
            $table->dropForeign(['pamp_metas_id']);
            $table->dropForeign(['pamp_logro_id']);
        });
        // Eliminamos la tabla
        Schema::dropIfExists('pamp_metas_logros');
        // Eliminamos los indices
        Schema::table('pamp_logros', function (Blueprint $table) {
            $table->dropIndex(['titulo']);
        });
        // Eliminamos las llaves unicas
        Schema::table('pamp_logros', function (Blueprint $table) {
            $table->dropUnique(['titulo']);
        });
        // Eliminamos la tabla
        Schema::dropIfExists('pamp_logros');
    }
};
