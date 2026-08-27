<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ResultadoLaboratorioImportado extends Model
{
    use HasFactory;

    protected $table = 'resultados_laboratorio_importados';

    protected $guarded = ['id'];

    protected $casts = [
        'fecha_resultado' => 'date',
    ];

    public function selloDigitalDataUri(): ?string
    {
        if (! $this->sello_digital || ! Storage::disk('public')->exists($this->sello_digital)) {
            return null;
        }

        $mime = Storage::disk('public')->mimeType($this->sello_digital) ?: 'image/png';

        return 'data:'.$mime.';base64,'.base64_encode(Storage::disk('public')->get($this->sello_digital));
    }
}
