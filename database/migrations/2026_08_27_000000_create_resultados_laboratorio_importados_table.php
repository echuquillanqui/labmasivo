<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resultados_laboratorio_importados', function (Blueprint $table) {
            $table->id();
            $table->uuid('lote_uuid')->index();
            $table->string('archivo_nombre');
            $table->date('fecha_resultado')->index();
            $table->string('procedencia', 150)->index();
            $table->string('dni', 30);
            $table->string('nombres_apellidos');

            foreach (['hto', 'hb', 'upre', 'upost', 'cloro', 'sodio', 'potasio',
                'fosforo_serico', 'calcio_serico', 'tgo', 'tgp', 'albumina_serica',
                'fosfatasa', 'hierro_serico', 'ferritina', 'transferrina', 'pth',
                'vih_1_2', 'rpr', 'hbsag', 'anti_hbs', 'anti_hbc_total', 'hcv', 'htlv_1_2'] as $campo) {
                $table->text($campo)->nullable();
            }

            $table->timestamps();
            $table->unique(['fecha_resultado', 'procedencia', 'dni'], 'resultados_lab_fecha_procedencia_dni_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resultados_laboratorio_importados');
    }
};
