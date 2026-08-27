<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('resultados_laboratorio_importados', function (Blueprint $table) {
            $table->string('sello_digital')->nullable()->after('archivo_nombre');
        });
    }

    public function down(): void
    {
        Schema::table('resultados_laboratorio_importados', function (Blueprint $table) {
            $table->dropColumn('sello_digital');
        });
    }
};
