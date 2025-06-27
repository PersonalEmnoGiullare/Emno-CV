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
        Schema::create('qr_dispositivo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('privada_id')->nullable()->constrained('qr_privadas')->onDelete('set null');
            $table->string('clave', 100)->unique();
            $table->string('direccion_ip');
            $table->timestamps();
            $table->softDeletes();
        });
        // Generamos llaves unicas
        Schema::table('qr_dispositivo', function (Blueprint $table) {
            $table->unique(['clave', 'direccion_ip'], 'unique_dispositivo_clave_ip');
        });
        // Generamos indices para mejorar el rendimiento de las consultas
        Schema::table('qr_dispositivo', function (Blueprint $table) {
            $table->index('privada_id', 'index_qr_d_privada_id');
            $table->index('clave', 'index_qr_d_clave');
            $table->index('direccion_ip', 'index_qr_d_direccion_ip');
            // Generamos indices compuestos
            $table->index(['privada_id', 'clave'], 'index_qr_d_vivienda_clave');
            $table->index(['privada_id', 'direccion_ip'], 'index_qr_d_vivienda_ip');
            $table->index(['clave', 'direccion_ip'], 'index_qr_d_clave_ip');
        });

        Schema::create('qr_accesos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('codigo_qr_id')->constrained('qr_codigos')->onDelete('cascade');
            $table->timestamp('fecha_hora')->useCurrent();
            $table->integer('num_uso')->default(1);
            $table->foreignId('dispositivo_id')->constrained('qr_dispositivo')->onDelete('cascade');
            $table->enum('resultado', ['permitido', 'denegado', 'expirado']);
            $table->json('fotografias')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        // Generamos llaves unicas
        Schema::table('qr_accesos', function (Blueprint $table) {
            $table->unique(['codigo_qr_id', 'fecha_hora'], 'unique_codigo_qr_fecha_hora');
        });
        // Generamos indices para mejorar el rendimiento de las consultas
        Schema::table('qr_accesos', function (Blueprint $table) {
            $table->index('codigo_qr_id', 'index_qr_a_codigo_qr_id');
            $table->index('dispositivo_id', 'index_qr_a_dispositivo');
            $table->index('fecha_hora', 'index_qr_a_fecha_hora');
            $table->index('num_uso', 'index_qr_a_num_uso');
            $table->index('resultado', 'index_qr_a_resultado');
            // Generamos indices compuestos
            $table->index(['codigo_qr_id', 'fecha_hora'], 'index_codigo_qr_fecha_hora');
            $table->index(['dispositivo_id', 'fecha_hora'], 'index_dispositivo_fecha_hora');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        // Eliminamos las llaves foraneas
        Schema::table('qr_accesos', function (Blueprint $table) {
            $table->dropForeign(['codigo_qr_id']);
            $table->dropForeign(['dispositivo_id']);
        });
        // Eliminamos las llaves unicas
        Schema::table('qr_accesos', function (Blueprint $table) {
            $table->dropUnique('unique_codigo_qr_fecha_hora');
        });
        // Eliminamos los indices compuestos
        Schema::table('qr_accesos', function (Blueprint $table) {
            $table->dropIndex('index_codigo_qr_fecha_hora');
            $table->dropIndex('index_dispositivo_fecha_hora');
        });
        // Eliminamos los indices
        Schema::table('qr_accesos', function (Blueprint $table) {
            $table->dropIndex('index_qr_a_codigo_qr_id');
            $table->dropIndex('index_qr_a_fecha_hora');
            $table->dropIndex('index_qr_a_num_uso');
            $table->dropIndex('index_qr_a_dispositivo');
            $table->dropIndex('index_qr_a_resultado');
        });
        // Eliminamos la tabla  
        Schema::dropIfExists('qr_accesos');


        // Eliminamos las llaves foraneas
        Schema::table('qr_dispositivo', function (Blueprint $table) {
            $table->dropForeign(['privada_id']);
        });
        // Eliminamos las llaves unicas
        Schema::table('qr_dispositivo', function (Blueprint $table) {
            $table->dropUnique('unique_dispositivo_clave_ip');
        });
        // Eliminamos los indices compuestos
        Schema::table('qr_dispositivo', function (Blueprint $table) {
            $table->dropIndex('index_qr_d_vivienda_clave');
            $table->dropIndex('index_qr_d_vivienda_ip');
            $table->dropIndex('index_qr_d_clave_ip');
        });
        // Eliminamos los indices
        Schema::table('qr_dispositivo', function (Blueprint $table) {
            $table->dropIndex('index_qr_d_privada_id');
            $table->dropIndex('index_qr_d_clave');
            $table->dropIndex('index_qr_d_direccion_ip');
        });
        // Eliminamos la tabla
        Schema::dropIfExists('qr_dispositivo');
    }
};
