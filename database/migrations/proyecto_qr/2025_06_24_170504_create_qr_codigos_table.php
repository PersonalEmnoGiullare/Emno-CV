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
        Schema::create('qr_codigos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invitado_id')->constrained('qr_invitados')->onDelete('cascade');
            $table->string('codigo', 100)->unique();
            $table->timestamp('fecha_generacion')->useCurrent();
            $table->timestamp('fecha_expiracion')->nullable();
            $table->timestamp('ultima_fecha_uso')->nullable();
            $table->integer('usos_restantes')->default(1);
            $table->enum('estado', ['activo', 'usado', 'expirado', 'cancelado'])->default('activo');
            $table->timestamps();
            $table->softDeletes();
        });
        // Generamos llaves unicas
        Schema::table('qr_codigos', function (Blueprint $table) {
            $table->unique(['invitado_id', 'codigo'], 'unique_invitado_codigo');
            $table->unique(['invitado_id', 'fecha_generacion'], 'unique_invitado_fecha_generacion');
        });
        // Generamos indices para mejorar el rendimiento de las consultas
        Schema::table('qr_codigos', function (Blueprint $table) {
            $table->index('invitado_id');
            $table->index('codigo');
            $table->index('fecha_generacion');
            $table->index('fecha_expiracion');
            $table->index('ultima_fecha_uso');
            $table->index('usos_restantes');
            $table->index('estado');
            // Generamos indices compuestos
            $table->index(['invitado_id', 'codigo'], 'index_invitado_codigo');
            $table->index(['invitado_id', 'fecha_generacion'], 'index_invitado_fecha_generacion');
            $table->index(['fecha_generacion', 'estado'], 'index_fecha_generacion_estado');
            $table->index(['fecha_expiracion', 'estado'], 'index_fecha_expiracion_estado');
            $table->index(['invitado_id', 'estado'], 'index_invitado_estado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Eliminamos los indices compuestos
        Schema::table('qr_codigos', function (Blueprint $table) {
            $table->dropIndex('index_invitado_codigo');
            $table->dropIndex('index_invitado_fecha_generacion');
            $table->dropIndex('index_fecha_generacion_estado');
            $table->dropIndex('index_fecha_expiracion_estado');
            $table->dropIndex('index_invitado_estado');
        });
        // Eliminamos los indices
        Schema::table('qr_codigos', function (Blueprint $table) {
            $table->dropIndex(['invitado_id']);
            $table->dropIndex(['codigo']);
            $table->dropIndex(['fecha_generacion']);
            $table->dropIndex(['fecha_expiracion']);
            $table->dropIndex(['ultima_fecha_uso']);
            $table->dropIndex(['usos_restantes']);
            $table->dropIndex(['estado']);
        });
        // Eliminamos las llaves unicas
        Schema::table('qr_codigos', function (Blueprint $table) {
            $table->dropUnique('unique_invitado_codigo');
            $table->dropUnique('unique_invitado_fecha_generacion');
        });
        // Eliminamos las llaves foraneas
        Schema::table('qr_codigos', function (Blueprint $table) {
            $table->dropForeign(['invitado_id']);
        });
        // Eliminamos la tabla
        Schema::dropIfExists('qr_codigos');
    }
};
