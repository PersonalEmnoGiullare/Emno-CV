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

        // creamos la tabla qr_invitados
        Schema::create('qr_invitados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('residente_id')->constrained('qr_residentes')->onDelete('cascade');
            $table->string('nombre', 100);
            $table->string('apellido_pat', 100);
            $table->string('apellido_mat', 100);
            $table->string('alias', 100)->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('motivo_visita', 200);
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
        // generamos llaves unicas
        Schema::table('qr_invitados', function (Blueprint $table) {
            $table->unique(['residente_id', 'nombre', 'apellido_pat', 'apellido_mat'], 'unique_resi_nom_ap');
            $table->unique(['residente_id', 'alias'], 'unique_resi_alias');
        });
        // generamos indices para mejorar el rendimiento de las consultas
        Schema::table('qr_invitados', function (Blueprint $table) {
            $table->index('residente_id');
            $table->index('nombre');
            $table->index('apellido_pat');
            $table->index('apellido_mat');
            $table->index('alias');
            $table->index('telefono');
            $table->index('motivo_visita');
            // indices compuestos
            $table->index(['residente_id', 'nombre', 'apellido_pat', 'apellido_mat'], 'index_resi_nom_ap');
            $table->index(['residente_id', 'alias'], 'index_resi_alias');
        });
        // creamos la tabla qr_vehiculos
        Schema::create('qr_vehiculos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invitado_id')->constrained('qr_invitados')->onDelete('cascade');
            $table->string('marca', 100);
            $table->string('modelo', 100);
            $table->string('color', 50);
            $table->string('placas', 20)->unique();
            $table->string('descripcion', 200)->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
        // generamos llaves unicas
        Schema::table('qr_vehiculos', function (Blueprint $table) {
            $table->unique(['invitado_id', 'marca', 'modelo', 'color', 'placas'], 'unique_inv_veh');
            $table->unique(['invitado_id', 'placas'], 'unique_inv_placas');
        });
        // generamos indices para mejorar el rendimiento de las consultas
        Schema::table('qr_vehiculos', function (Blueprint $table) {
            $table->index('invitado_id');
            $table->index('marca');
            $table->index('modelo');
            $table->index('color');
            $table->index('placas');
            $table->index('descripcion');
            $table->index('activo');
            // indices compuestos
            $table->index(['invitado_id', 'marca', 'modelo', 'color', 'placas'], 'index_inv_veh');
            $table->index(['invitado_id', 'placas'], 'index_inv_placas');
            $table->index(['invitado_id', 'placas', 'activo'], 'index_inv_placas_activo');
            $table->index(['marca', 'modelo'], 'index_marca_modelo');
            $table->index(['marca', 'modelo', 'color'], 'index_marca_modelo_color');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Eliminamos los indices compuestos
        Schema::table('qr_vehiculos', function (Blueprint $table) {
            $table->dropIndex('index_inv_veh');
            $table->dropIndex('index_inv_placas');
            $table->dropIndex('index_inv_placas_activo');
            $table->dropIndex('index_marca_modelo');
            $table->dropIndex('index_marca_modelo_color');
        });
        // Eliminamos los indices
        Schema::table('qr_vehiculos', function (Blueprint $table) {
            $table->dropIndex(['invitado_id']);
            $table->dropIndex(['marca']);
            $table->dropIndex(['modelo']);
            $table->dropIndex(['color']);
            $table->dropIndex(['placas']);
            $table->dropIndex(['descripcion']);
            $table->dropIndex(['activo']);
        });
        // Eliminamos las llaves unicas
        Schema::table('qr_vehiculos', function (Blueprint $table) {
            $table->dropUnique('unique_inv_veh');
            $table->dropUnique('unique_inv_placas');
        });
        // Eliminamos las llaves foraneas
        Schema::table('qr_vehiculos', function (Blueprint $table) {
            $table->dropForeign(['invitado_id']);
        });
        // Eliminamos la tabla qr_vehiculos
        Schema::dropIfExists('qr_vehiculos');
        // Eliminamos los indices compuestos
        Schema::table('qr_invitados', function (Blueprint $table) {
            $table->dropIndex('index_resi_nom_ap');
            $table->dropIndex('index_resi_alias');
        });
        // Eliminamos los indices
        Schema::table('qr_invitados', function (Blueprint $table) {
            $table->dropIndex(['residente_id']);
            $table->dropIndex(['nombre']);
            $table->dropIndex(['apellido_pat']);
            $table->dropIndex(['apellido_mat']);
            $table->dropIndex(['alias']);
            $table->dropIndex(['telefono']);
            $table->dropIndex(['motivo_visita']);
        });
        // Eliminamos las llaves unicas
        Schema::table('qr_invitados', function (Blueprint $table) {
            $table->dropUnique('unique_resi_nom_ap');
            $table->dropUnique('unique_resi_alias');
        });
        // Eliminamos las llaves foraneas
        Schema::table('qr_invitados', function (Blueprint $table) {
            $table->dropForeign(['residente_id']);
        });
        // Eliminamos la tabla qr_invitados
        Schema::dropIfExists('qr_invitados');
    }
};
