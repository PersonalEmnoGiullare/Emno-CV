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
        Schema::create('qr_residentes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('privada_id')->constrained('qr_privadas')->onDelete('cascade');
            $table->foreignId('vivienda_id')->constrained('qr_viviendas')->onDelete('cascade');
            $table->foreignId('usuario_id')->nullable()->constrained('users');
            $table->string('telefono', 20)->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // Generamos llaves unicas
        Schema::table('qr_residentes', function (Blueprint $table) {
            $table->unique(['privada_id', 'vivienda_id'], 'unique_privada_vivienda');
            $table->unique(['usuario_id', 'vivienda_id'], 'unique_usuario_vivienda');
        });
        // Generamos indices para mejorar el rendimiento de las consultas
        Schema::table('qr_residentes', function (Blueprint $table) {
            $table->index('privada_id');
            $table->index('vivienda_id');
            $table->index('usuario_id');
            $table->index('telefono');
            $table->index('activo');
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
        // Eliminamos los indices compuestos
        Schema::table('qr_residentes', function (Blueprint $table) {
            $table->dropIndex('index_privada_vivienda');
            $table->dropIndex('index_usuario_vivienda');
            $table->dropIndex('index_usuario_privada');
            $table->dropIndex('index_vivienda_activo');
        });
        // Eliminamos los indices
        Schema::table('qr_residentes', function (Blueprint $table) {
            $table->dropIndex(['privada_id']);
            $table->dropIndex(['vivienda_id']);
            $table->dropIndex(['usuario_id']);
            $table->dropIndex(['telefono']);
            $table->dropIndex(['activo']);
        });
        // Eliminamos las llaves unicas
        Schema::table('qr_residentes', function (Blueprint $table) {
            $table->dropUnique('unique_privada_vivienda');
            $table->dropUnique('unique_usuario_vivienda');
        });
        // Eliminamos las llaves foraneas
        Schema::table('qr_residentes', function (Blueprint $table) {
            $table->dropForeign(['privada_id']);
            $table->dropForeign(['vivienda_id']);
            $table->dropForeign(['usuario_id']);
        });
        // Eliminamos la tabla
        Schema::dropIfExists('qr_residentes');
    }
};
