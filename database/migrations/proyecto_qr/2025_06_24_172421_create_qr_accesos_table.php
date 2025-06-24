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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Eliminamos los indices compuestos
        Schema::table('qr_accesos', function (Blueprint $table) {
            $table->dropIndex(['codigo_qr_id', 'fecha_hora']);
        });
        // Eliminamos los indices
        Schema::table('qr_accesos', function (Blueprint $table) {
            $table->dropIndex(['codigo_qr_id']);
            $table->dropIndex(['fecha_hora']);
            $table->dropIndex(['num_uso']);
            $table->dropIndex(['dispositivo']);
            $table->dropIndex(['direccion_ip']);
            $table->dropIndex(['ubicacion']);
            $table->dropIndex(['resultado']);
        });
        // Eliminamos las llaves unicas
        Schema::table('qr_accesos', function (Blueprint $table) {
            $table->dropUnique(['codigo_qr_id', 'fecha_hora']);
        });
        // Eliminamos las llaves foraneas
        Schema::table('qr_accesos', function (Blueprint $table) {
            $table->dropForeign(['codigo_qr_id']);
        });
        // Eliminamos la tabla  
        Schema::dropIfExists('qr_accesos');
    }
};
