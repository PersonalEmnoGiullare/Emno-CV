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
            $table->index('residente_id', 'index_qr_i_residente_id');
            $table->index('nombre', 'index_qr_i_nombre');
            $table->index('apellido_pat', 'index_qr_i_apellido_pat');
            $table->index('apellido_mat', 'index_qr_i_apellido_mat');
            $table->index('alias', 'index_qr_i_alias');
            $table->index('telefono', 'index_qr_i_telefono');
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
            $table->index('invitado_id', 'index_qr_v_invitado_id');
            $table->index('marca', 'index_qr_v_marca');
            $table->index('modelo', 'index_qr_v_modelo');
            $table->index('color', 'index_qr_v_color');
            $table->index('placas', 'index_qr_v_placas');
            $table->index('descripcion', 'index_qr_v_descripcion');
            $table->index('activo', 'index_qr_v_activo');
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
        // Eliminamos las llaves foraneas
        Schema::table('qr_vehiculos', function (Blueprint $table) {
            $table->dropForeign(['invitado_id']);
        });
        // Eliminamos las llaves unicas
        Schema::table('qr_vehiculos', function (Blueprint $table) {
            $table->dropUnique('unique_inv_veh');
            $table->dropUnique('unique_inv_placas');
        });
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
            $table->dropIndex('index_qr_v_invitado_id');
            $table->dropIndex('index_qr_v_marca');
            $table->dropIndex('index_qr_v_modelo');
            $table->dropIndex('index_qr_v_color');
            $table->dropIndex('index_qr_v_placas');
            $table->dropIndex('index_qr_v_descripcion');
            $table->dropIndex('index_qr_v_activo');
        });
        // Eliminamos la tabla qr_vehiculos
        Schema::dropIfExists('qr_vehiculos');


        // Eliminamos las llaves foraneas
        Schema::table('qr_invitados', function (Blueprint $table) {
            $table->dropForeign(['residente_id']);
        });
        // Eliminamos las llaves unicas
        Schema::table('qr_invitados', function (Blueprint $table) {
            $table->dropUnique('unique_resi_nom_ap');
            $table->dropUnique('unique_resi_alias');
        });
        // Eliminamos los indices compuestos
        Schema::table('qr_invitados', function (Blueprint $table) {
            $table->dropIndex('index_resi_nom_ap');
            $table->dropIndex('index_resi_alias');
        });
        // Eliminamos los indices
        Schema::table('qr_invitados', function (Blueprint $table) {
            $table->dropIndex('index_qr_i_residente_id');
            $table->dropIndex('index_qr_i_nombre');
            $table->dropIndex('index_qr_i_apellido_pat');
            $table->dropIndex('index_qr_i_apellido_mat');
            $table->dropIndex('index_qr_i_alias');
            $table->dropIndex('index_qr_i_telefono');
        });
        // Eliminamos la tabla qr_invitados
        Schema::dropIfExists('qr_invitados');
    }
};
