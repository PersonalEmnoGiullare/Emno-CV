<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rules\Unique;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('qr_residentes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('privada_id')->nullable()->constrained('qr_privadas')->onDelete('set null');
            $table->foreignId('vivienda_id')->nullable()->constrained('qr_viviendas')->onDelete('set null');
            $table->foreignId('usuario_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('telefono', 20)->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // Generamos llaves unicas
        Schema::table('qr_residentes', function (Blueprint $table) {
            $table->unique(['usuario_id', 'vivienda_id'], 'unique_usuario_vivienda');
            $table->unique(['usuario_id'], 'unique_usuario_id');
        });
        // Generamos indices para mejorar el rendimiento de las consultas
        Schema::table('qr_residentes', function (Blueprint $table) {
            $table->index('privada_id', 'index_qr_r_privada_id');
            $table->index('vivienda_id', 'index_qr_r_vivienda_id');
            $table->index('usuario_id', 'index_qr_r_usuario_id');
            $table->index('telefono', 'index_qr_r_telefono');
            $table->index('activo', 'index_qr_r_activo');
            // Indices compuestos
            $table->index(['privada_id', 'vivienda_id'], 'index_privada_vivienda');
            $table->index(['usuario_id', 'vivienda_id'], 'index_usuario_vivienda');
            $table->index(['usuario_id', 'privada_id'], 'index_usuario_privada');
            $table->index(['vivienda_id', 'activo'], 'index_vivienda_activo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Eliminamos las llaves foraneas
        Schema::table('qr_residentes', function (Blueprint $table) {
            $table->dropForeign(['privada_id']);
            $table->dropForeign(['vivienda_id']);
            $table->dropForeign(['usuario_id']);
        });
        // Eliminamos las llaves unicas
        Schema::table('qr_residentes', function (Blueprint $table) {
            $table->dropUnique('unique_usuario_vivienda');
            $table->dropUnique('unique_usuario_id');
        });
        // Eliminamos los indices compuestos
        Schema::table('qr_residentes', function (Blueprint $table) {
            $table->dropIndex('index_privada_vivienda');
            $table->dropIndex('index_usuario_vivienda');
            $table->dropIndex('index_usuario_privada');
            $table->dropIndex('index_vivienda_activo');
        });
        // Eliminamos los indices
        Schema::table('qr_residentes', function (Blueprint $table) {
            $table->dropIndex('index_qr_r_privada_id');
            $table->dropIndex('index_qr_r_vivienda_id');
            $table->dropIndex('index_qr_r_usuario_id');
            $table->dropIndex('index_qr_r_telefono');
            $table->dropIndex('index_qr_r_activo');
        });
        // Eliminamos la tabla
        Schema::dropIfExists('qr_residentes');
    }
};
