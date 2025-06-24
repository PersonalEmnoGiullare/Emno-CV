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
        Schema::create('pamp_guerreros', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->nullable()->constrained('users');
            $table->string('avatar')->nullable();
            $table->date('start_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        // Generamos llaves unicas
        Schema::table('pamp_guerreros', function (Blueprint $table) {
            $table->unique('usuario_id', 'unique_usuario_id');
        });
        // Generamos indices para mejorar el rendimiento de las consultas
        Schema::table('pamp_guerreros', function (Blueprint $table) {
            $table->index('usuario_id');
            $table->index('start_date');
        });
        // Generamos un indice compuesto
        Schema::table('pamp_guerreros', function (Blueprint $table) {
            $table->index(['usuario_id', 'start_date'], 'index_usuario_start_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Eliminamos los indices compuestos
        Schema::table('pamp_guerreros', function (Blueprint $table) {
            $table->dropIndex('index_usuario_start_date');
        });
        // Eliminamos los indices
        Schema::table('pamp_guerreros', function (Blueprint $table) {
            $table->dropIndex(['usuario_id']);
            $table->dropIndex(['start_date']);
        });
        // Eliminamos las llaves unicas
        Schema::table('pamp_guerreros', function (Blueprint $table) {
            $table->dropUnique('unique_usuario_id');
        });
        // Eliminamos las llaves foraneas
        Schema::table('pamp_guerreros', function (Blueprint $table) {
            $table->dropForeign(['usuario_id']);
        });
        // Eliminamos la tabla
        Schema::dropIfExists('pamp_guerreros');
    }
};
