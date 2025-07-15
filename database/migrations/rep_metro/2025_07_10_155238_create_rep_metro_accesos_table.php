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
        Schema::create('rep_metro_tipos_pago', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 60)->unique();
            $table->string('descripcion', 255)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        // Generamos indices para mejorar el rendimiento de las consultas
        Schema::table('rep_metro_tipos_pago', function (Blueprint $table) {
            $table->index('nombre', 'index_tipos_pago_nombre');
            $table->index('descripcion', 'index_tipos_pago_descripcion');
        });


        Schema::create('rep_metro_tarifas', function (Blueprint $table) {
            $table->id();
            $table->decimal('importe', 6, 2);
            $table->date('fecha_inicio');
            $table->date('fecha_fin')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Generamos indices para mejorar el rendimiento de las consultas
        Schema::table('rep_metro_tarifas', function (Blueprint $table) {
            $table->index('importe', 'index_tar_importe');
            $table->index('fecha_inicio', 'index_tar_fecha_inicio');
            $table->index('fecha_fin', 'index_tar_fecha_fin');
            // generamos indices compuestos, comparar entre fecha de inicio y fin
            $table->index(['fecha_inicio', 'fecha_fin'], 'index_tar_fecha_inicio_fin');
        });

        Schema::create('rep_metro_accesos', function (Blueprint $table) {
            $table->id();
            $table->dateTime('fecha_hora')->useCurrent();
            $table->foreignId('id_estacion')->constrained('rep_metro_estaciones')->onDelete('restrict')->onUpdate('cascade');
            $table->foreignId('id_tipo_pago')->constrained('rep_metro_tipos_pago')->onDelete('restrict')->onUpdate('cascade');
            $table->foreignId('id_tarifa')->constrained('rep_metro_tarifas')->onDelete('restrict')->onUpdate('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
        // Generamos indices para mejorar el rendimiento de las consultas
        Schema::table('rep_metro_accesos', function (Blueprint $table) {
            $table->index('fecha_hora', 'index_accesos_fecha_hora');
            $table->index('id_estacion', 'index_accesos_estacion');
            $table->index('id_tipo_pago', 'index_accesos_tipo_pago');
            $table->index('id_tarifa', 'index_accesos_tarifa');
            // generamos indices compuestos
            $table->index(['fecha_hora', 'id_estacion'], 'index_acc_fecha_hora_estacion');
            $table->index(['fecha_hora', 'id_tipo_pago'], 'index_acc_fecha_hora_tipo_pago');
            $table->index(['fecha_hora', 'id_tarifa'], 'index_acc_fecha_hora_tarifa');
            $table->index(['id_estacion', 'id_tipo_pago'], 'index_acc_estacion_tipo_pago');
            $table->index(['id_estacion', 'id_tarifa'], 'index_acc_estacion_tarifa');
        });
        // Generamos llaves unicas
        Schema::table('rep_metro_accesos', function (Blueprint $table) {
            $table->unique(['fecha_hora', 'id_estacion', 'id_tipo_pago', 'id_tarifa'], 'unique_acc_fecha_hora_estacion_tipo_pago_tarifa');
        });


        Schema::create('rep_metro_frecuencias_acceso', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_estacion')->constrained('rep_metro_estaciones')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('id_tipo_pago')->constrained('rep_metro_tipos_pago')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('id_tarifa')->constrained('rep_metro_tarifas')->onDelete('restrict')->onUpdate('cascade');
            $table->date('periodo');
            $table->integer('cantidad')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
        // Generamos indices para mejorar el rendimiento de las consultas
        Schema::table('rep_metro_frecuencias_acceso', function (Blueprint $table) {
            $table->index('id_estacion', 'index_frec_estacion');
            $table->index('id_tipo_pago', 'index_frec_tipo_pago');
            $table->index('id_tarifa', 'index_frec_tarifa');
            $table->index('periodo', 'index_frec_periodo');
            $table->index('cantidad', 'index_frec_cantidad');
            // generamos indices compuestos
            $table->index(['id_estacion', 'id_tipo_pago'], 'index_frec_estacion_tipo_pago');
            $table->index(['id_estacion', 'id_tarifa'], 'index_frec_estacion_tarifa');
            $table->index(['id_tipo_pago', 'id_tarifa'], 'index_frec_tipo_pago_tarifa');
            $table->index(['id_estacion', 'periodo'], 'index_frec_estacion_periodo');
            $table->index(['id_tipo_pago', 'periodo'], 'index_frec_tipo_pago_periodo');
            $table->index(['id_tarifa', 'periodo'], 'index_frec_tarifa_periodo');
            $table->index(['id_estacion', 'id_tipo_pago', 'id_tarifa'], 'index_frec_estacion_tipo_pago_tarifa');
            $table->index(['id_estacion', 'id_tipo_pago', 'periodo'], 'index_frec_estacion_tipo_pago_periodo');
            $table->index(['id_estacion', 'id_tarifa', 'periodo'], 'index_frec_estacion_tarifa_periodo');
            $table->index(['id_tipo_pago', 'id_tarifa', 'periodo'], 'index_frec_tipo_pago_tarifa_periodo');
        });

        // creamos llaves unicas para estacion, tarifa, tipo pago y periodo
        Schema::table('rep_metro_frecuencias_acceso', function (Blueprint $table) {
            $table->unique(['id_estacion', 'id_tipo_pago', 'id_tarifa', 'periodo'], 'unique_frec_estacion_tipo_pago_tarifa_periodo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Eliminamos llaves foraneas de la tabla feciencias_acceso
        Schema::table('rep_metro_frecuencias_acceso', function (Blueprint $table) {
            $table->dropForeign(['id_estacion']);
            $table->dropForeign(['id_tipo_pago']);
            $table->dropForeign(['id_tarifa']);
        });
        // Eliminamos llaves unicas de la tabla feciencias_acceso
        Schema::table('rep_metro_frecuencias_acceso', function (Blueprint $table) {
            $table->dropUnique('unique_frec_estacion_tipo_pago_tarifa_periodo');
        });
        // Eliminamos indices compuestos de la tabla feciencias_acceso
        Schema::table('rep_metro_frecuencias_acceso', function (Blueprint $table) {
            $table->dropIndex('index_frec_estacion_tipo_pago');
            $table->dropIndex('index_frec_estacion_tarifa');
            $table->dropIndex('index_frec_tipo_pago_tarifa');
            $table->dropIndex('index_frec_estacion_periodo');
            $table->dropIndex('index_frec_tipo_pago_periodo');
            $table->dropIndex('index_frec_tarifa_periodo');
            $table->dropIndex('index_frec_estacion_tipo_pago_tarifa');
            $table->dropIndex('index_frec_estacion_tipo_pago_periodo');
            $table->dropIndex('index_frec_estacion_tarifa_periodo');
            $table->dropIndex('index_frec_tipo_pago_tarifa_periodo');
        });
        // Eliminamos indices de la tabla feciencias_acceso
        Schema::table('rep_metro_frecuencias_acceso', function (Blueprint $table) {
            $table->dropIndex('index_frec_estacion');
            $table->dropIndex('index_frec_tipo_pago');
            $table->dropIndex('index_frec_tarifa');
            $table->dropIndex('index_frec_periodo');
            $table->dropIndex('index_frec_cantidad');
        });
        // Eliminamos la tabla feciencias_acceso
        Schema::dropIfExists('rep_metro_frecuencias_acceso');

        // Eliminamos llaves foraneas de la tabla accesos
        Schema::table('rep_metro_accesos', function (Blueprint $table) {
            $table->dropForeign(['id_estacion']);
            $table->dropForeign(['id_tipo_pago']);
            $table->dropForeign(['id_tarifa']);
        });
        // Eliminamos llaves unicas de la tabla accesos
        Schema::table('rep_metro_accesos', function (Blueprint $table) {
            $table->dropUnique('unique_acc_fecha_hora_estacion_tipo_pago_tarifa');
        });
        // Eliminamos indices compuestos de la tabla accesos
        Schema::table('rep_metro_accesos', function (Blueprint $table) {
            $table->dropIndex('index_acc_fecha_hora_estacion');
            $table->dropIndex('index_acc_fecha_hora_tipo_pago');
            $table->dropIndex('index_acc_fecha_hora_tarifa');
            $table->dropIndex('index_acc_estacion_tipo_pago');
            $table->dropIndex('index_acc_estacion_tarifa');
        });
        // Eliminamos indices de la tabla accesos
        Schema::table('rep_metro_accesos', function (Blueprint $table) {
            $table->dropIndex('index_accesos_fecha_hora');
            $table->dropIndex('index_accesos_estacion');
            $table->dropIndex('index_accesos_tipo_pago');
            $table->dropIndex('index_accesos_tarifa');
        });
        // Eliminamos la tabla accesos
        Schema::dropIfExists('rep_metro_accesos');

        // Eliminamos indices de tarifas
        Schema::table('rep_metro_tarifas', function (Blueprint $table) {
            $table->dropIndex('index_tar_importe');
            $table->dropIndex('index_tar_fecha_inicio');
            $table->dropIndex('index_tar_fecha_fin');
            $table->dropIndex('index_tar_fecha_inicio_fin');
        });
        // Eliminamos la tabla tarifas
        Schema::dropIfExists('rep_metro_tarifas');


        // Eliminamos indices de tipos_pago
        Schema::table('rep_metro_tipos_pago', function (Blueprint $table) {
            $table->dropIndex('index_tipos_pago_nombre');
            $table->dropIndex('index_tipos_pago_descripcion');
        });
        // Eliminamos la tabla tipos_pago
        Schema::dropIfExists('rep_metro_tipos_pago');
    }
};
