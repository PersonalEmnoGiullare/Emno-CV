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
        Schema::create('qr_viviendas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('privada_id')->constrained('qr_privadas')->onDelete('cascade');
            $table->string('numero', 20);
            $table->string('tipo', 50);
            $table->string('calle', 100)->nullable();
            $table->string('seccion', 50)->nullable();
            $table->boolean('disponible')->default(true);
            $table->text('observaciones')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        // Generamos llaves unicas
        Schema::table('qr_viviendas', function (Blueprint $table) {
            $table->unique(['privada_id', 'calle', 'numero'], 'unique_priv_call_num');
        });
        // Generamos indices para mejorar el rendimiento de las consultas
        Schema::table('qr_viviendas', function (Blueprint $table) {
            $table->index('privada_id', 'index_qr_v_privada_id');
            $table->index('numero', 'index_qr_v_numero');
            $table->index('tipo', 'index_qr_v_tipo');
            $table->index('calle', 'index_qr_v_calle');
            $table->index('seccion', 'index_qr_v_seccion');
            $table->index('disponible', 'index_qr_v_disponible');
            // Indices compuestos
            $table->index(['privada_id', 'calle', 'numero'], 'index_priv_call_num');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // eliminamos llaves foraneas
        Schema::table('qr_viviendas', function (Blueprint $table) {
            $table->dropForeign(['privada_id']);
        });
        // Eliminamos las llaves unicas
        Schema::table('qr_viviendas', function (Blueprint $table) {
            $table->dropUnique('unique_priv_call_num');
        });
        // Eliminamos los indices compuestos
        Schema::table('qr_viviendas', function (Blueprint $table) {
            $table->dropIndex('index_priv_call_num');
        });
        // Eliminamos los indices
        Schema::table('qr_viviendas', function (Blueprint $table) {
            $table->dropIndex('index_qr_v_privada_id');
            $table->dropIndex('index_qr_v_numero');
            $table->dropIndex('index_qr_v_tipo');
            $table->dropIndex('index_qr_v_calle');
            $table->dropIndex('index_qr_v_seccion');
            $table->dropIndex('index_qr_v_disponible');
        });
        // Eliminamos la tabla
        Schema::dropIfExists('qr_viviendas');
    }
};
