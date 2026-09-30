<!doctype html>
<html lang="es"><head><meta charset="utf-8"><title>Resultados de laboratorio</title><style>
@page { size: A4 portrait; margin: 30mm 18mm 18mm; }
* { box-sizing: border-box; } body { margin: 0; color: #111; font-family: DejaVu Sans, sans-serif; font-size: 6.2pt; }
.resultado-paciente { width: 100%; page-break-after: always; page-break-inside: avoid; }
.resultado-paciente:last-child { page-break-after: auto; }
h1 { margin: 0 0 2mm; text-align: center; font-size: 9pt; letter-spacing: .2px; text-decoration: underline; }
.datos { width: 100%; margin-bottom: 1mm; border-collapse: collapse; }.datos td { padding: .35mm .7mm; border: 0; }.datos .etiqueta { font-weight: bold; width: 17%; }
h2 { font-size: 7pt; text-align: center; text-transform: uppercase; margin: 1mm 0 .45mm; text-decoration: underline; }
.resultados { width: 100%; border-collapse: collapse; page-break-inside: avoid; table-layout: fixed; }
.resultados th,.resultados td { border: .2mm solid #222; padding: .3mm .7mm; line-height: 1.05; }
.resultados thead th { background: #d9d9d9; text-align: center; font-size: 6.1pt; }
.resultados .nombre { width: 47%; }.resultados .valor { width: 16%; text-align: center; }.resultados .unidad { width: 13%; text-align: center; }.resultados .referencia { width: 24%; text-align: center; }
.subtitulo td { background: #eeeeee; font-weight: bold; }.sello { margin-top: 1mm; text-align: center; page-break-inside: avoid; }.sello img { display: block; margin: 0 auto; max-width: 45mm; max-height: 20mm; }
</style></head><body>

@foreach($resultados as $resultado)
<div class="resultado-paciente">
    <h1>RESULTADOS DE LABORATORIO</h1>
    <table class="datos"><tr><td class="etiqueta">PACIENTE:</td><td colspan="3">{{ $resultado->nombres_apellidos }}</td></tr><tr><td class="etiqueta">DNI:</td><td>{{ $resultado->dni }}</td><td class="etiqueta">FECHA:</td><td>{{ $resultado->fecha_resultado->format('d/m/Y') }}</td></tr><tr><td class="etiqueta">PROCEDENCIA:</td><td colspan="3">{{ $resultado->procedencia }}</td></tr></table>
    @foreach($analisis as $area => $items)
        @php
            $itemsConResultado = collect($items)->filter(function ($item) use ($resultado) {
                return empty($item['encabezado'])
                    && filled($resultado->{$item['campo']});
            });
        @endphp
        @if($itemsConResultado->isNotEmpty())
        <h2>{{ $area }}</h2>
        <table class="resultados"><thead><tr><th class="nombre">ANÁLISIS</th><th class="valor">RESULTADO</th><th class="unidad">UNIDAD</th><th class="referencia">VALORES DE REFERENCIA</th></tr></thead><tbody>
        @foreach($itemsConResultado as $item)
            <tr><td>{{ $item['nombre'] }}</td><td class="valor">{{ $resultado->{$item['campo']} }}</td><td class="unidad">{{ $item['unidad'] }}</td><td class="referencia">{{ $item['referencia'] }}</td></tr>
        @endforeach
        </tbody></table>
        @endif
    @endforeach
    @if($sello = $resultado->selloDigitalDataUri() ?? ($selloUsuario ?? null))<div class="sello"><img src="{{ $sello }}" alt="Sello digital"></div>@endif
</div>
@endforeach
</body></html>
