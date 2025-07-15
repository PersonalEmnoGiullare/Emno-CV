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
        Schema::create('rep_metro_departamentos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 60)->unique();
            $table->text('descripcion')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        // Generamos indices para mejorar el rendimiento de las consultas
        Schema::table('rep_metro_departamentos', function (Blueprint $table) {
            $table->index('nombre', 'index_departamentos_nombre');
            $table->index('descripcion', 'index_departamentos_descripcion');
        });

        // Creamos la tabla puestos
        Schema::create('rep_metro_puestos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 60)->unique();
            $table->text('descripcion')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        // Generamos indices para mejorar el rendimiento de las consultas
        Schema::table('rep_metro_puestos', function (Blueprint $table) {
            $table->index('nombre', 'index_puestos_nombre');
            $table->index('descripcion', 'index_puestos_descripcion');
        });
        // Creamos la tabla empleados
        Schema::create('rep_metro_empleado', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_usuario')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('id_departamento')->nullable()->constrained('rep_metro_departamentos')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('id_puesto')->nullable()->constrained('rep_metro_puestos')->onDelete('set null')->onUpdate('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
        // Generamos indices para mejorar el rendimiento de las consultas
        Schema::table('rep_metro_empleado', function (Blueprint $table) {
            $table->index('id_usuario', 'index_empleado_usuario');
            $table->index('id_departamento', 'index_empleado_departamento');
            $table->index('id_puesto', 'index_empleado_puesto');
        });
        // Generamos llaves unicas
        Schema::table('rep_metro_empleado', function (Blueprint $table) {
            $table->unique('id_usuario', 'unique_empleado_usuario');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Eliminamos las llaves foraneas
        Schema::table('rep_metro_empleado', function (Blueprint $table) {
            $table->dropForeign(['id_usuario']);
            $table->dropForeign(['id_departamento']);
            $table->dropForeign(['id_puesto']);
        });
        // Eliminamos las llaves unicas
        Schema::table('rep_metro_empleado', function (Blueprint $table) {
            $table->dropUnique('unique_empleado_usuario');
        });
        // Eliminamos los indices
        Schema::table('rep_metro_empleado', function (Blueprint $table) {
            $table->dropIndex('index_empleado_usuario');
            $table->dropIndex('index_empleado_departamento');
            $table->dropIndex('index_empleado_puesto');
        });
        // Eliminamos la tabla empleado
        Schema::dropIfExists('rep_metro_empleado');


        // Eliminamos los indices de puestos
        Schema::table('rep_metro_puestos', function (Blueprint $table) {
            $table->dropIndex('index_puestos_nombre');
            $table->dropIndex('index_puestos_descripcion');
        });
        // Eliminamos la tabla puestos
        Schema::dropIfExists('rep_metro_puestos');


        // Eliminamos los indices de departamentos
        Schema::table('rep_metro_departamentos', function (Blueprint $table) {
            $table->dropIndex('index_departamentos_nombre');
            $table->dropIndex('index_departamentos_descripcion');
        });
        // Eliminamos la tabla departamentos
        Schema::dropIfExists('rep_metro_departamentos');
    }
};
