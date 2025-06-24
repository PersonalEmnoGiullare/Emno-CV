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
        Schema::create('pamp_horarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pamp_guerrero_id')->constrained('pamp_guerreros')->onDelete('cascade');
            $table->foreignId('meta_id')->constrained('pamp_metas')->onDelete('cascade');

            // Configuración de periodicidad
            $table->integer('periodicidad')->nullable();

            // Días y horarios
            $table->json('horarios')->nullable();

            // Configuración de repetición
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_termino')->nullable();

            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
        // Generamos indices para mejorar el rendimiento de las consultas
        Schema::table('pamp_horarios', function (Blueprint $table) {
            $table->index('pamp_guerrero_id');
            $table->index('meta_id');
        });
        // Generamos un indice compuesto
        Schema::table('pamp_horarios', function (Blueprint $table) {
            $table->index(['pamp_guerrero_id', 'meta_id'], 'index_pamp_guerrero_meta');
        });
        // Generamos llaves unicas
        Schema::table('pamp_horarios', function (Blueprint $table) {
            $table->unique(['pamp_guerrero_id', 'meta_id'], 'unique_pamp_guerrero_meta');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Eliminamos los indices compuestos
        Schema::table('pamp_horarios', function (Blueprint $table) {
            $table->dropIndex(['pamp_guerrero_id', 'meta_id']);
        });
        // Eliminamos los indices
        Schema::table('pamp_horarios', function (Blueprint $table) {
            $table->dropIndex(['pamp_guerrero_id']);
            $table->dropIndex(['meta_id']);
        });
        // Eliminamos las llaves foraneas
        Schema::table('pamp_horarios', function (Blueprint $table) {
            $table->dropForeign(['pamp_guerrero_id']);
            $table->dropForeign(['meta_id']);
        });
        // Eliminamos la tabla
        Schema::dropIfExists('pamp_horarios');
    }
};
