<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResultadoLaboratorioImportado extends Model
{
    use HasFactory;

    protected $table = 'resultados_laboratorio_importados';

    protected $guarded = ['id'];

    protected $casts = [
        'fecha_resultado' => 'date',
    ];
}
