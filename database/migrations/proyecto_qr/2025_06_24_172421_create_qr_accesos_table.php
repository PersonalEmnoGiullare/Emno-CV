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
        Schema::create('qr_accesos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('codigo_qr_id')->constrained('qr_codigos')->onDelete('cascade');
            $table->timestamp('fecha_hora')->useCurrent();
            $table->integer('num_uso')->default(1);
            $table->string('dispositivo', 50)->nullable();
            $table->string('direccion_ip', 45)->nullable();
            $table->string('ubicacion', 100)->nullable();
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
            $table->index('fecha_hora', 'index_qr_a_fecha_hora');
            $table->index('num_uso', 'index_qr_a_num_uso');
            $table->index('dispositivo', 'index_qr_a_dispositivo');
            $table->index('direccion_ip', 'index_qr_a_direccion_ip');
            $table->index('ubicacion', 'index_qr_a_ubicacion');
            $table->index('resultado', 'index_qr_a_resultado');
            // Generamos indices compuestos
            $table->index(['codigo_qr_id', 'fecha_hora'], 'index_codigo_qr_fecha_hora');
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
        });
        // Eliminamos las llaves unicas
        Schema::table('qr_accesos', function (Blueprint $table) {
            $table->dropUnique('unique_codigo_qr_fecha_hora');
        });
        // Eliminamos los indices compuestos
        Schema::table('qr_accesos', function (Blueprint $table) {
            $table->dropIndex('index_codigo_qr_fecha_hora');
        });
        // Eliminamos los indices
        Schema::table('qr_accesos', function (Blueprint $table) {
            $table->dropIndex('index_qr_a_codigo_qr_id');
            $table->dropIndex('index_qr_a_fecha_hora');
            $table->dropIndex('index_qr_a_num_uso');
            $table->dropIndex('index_qr_a_dispositivo');
            $table->dropIndex('index_qr_a_direccion_ip');
            $table->dropIndex('index_qr_a_ubicacion');
            $table->dropIndex('index_qr_a_resultado');
        });
        // Eliminamos la tabla  
        Schema::dropIfExists('qr_accesos');
    }
};
