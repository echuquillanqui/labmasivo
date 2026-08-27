<!doctype html>
<html lang="es"><head><meta charset="utf-8"><title>Resultados de laboratorio</title><style>
@page { size: A4 portrait; margin: 25mm 13mm 13mm; }
* { box-sizing: border-box; } body { margin: 0; color: #111; font-family: DejaVu Sans, sans-serif; font-size: 7.2pt; }
.resultado-paciente { page-break-after: always; min-height: 259mm; position: relative; padding-bottom: 27mm; }
.resultado-paciente:last-child { page-break-after: auto; }
h1 { margin: 0 0 3mm; text-align: center; font-size: 13pt; letter-spacing: .4px; }
.datos { width: 100%; margin-bottom: 2mm; border-collapse: collapse; }.datos td { padding: .6mm 1mm; border: 0; }.datos .etiqueta { font-weight: bold; width: 17%; }
h2 { font-size: 8.5pt; text-align: center; text-transform: uppercase; margin: 1.5mm 0 .7mm; }
.resultados { width: 100%; border-collapse: collapse; page-break-inside: avoid; table-layout: fixed; }
.resultados th,.resultados td { border: .25mm solid #222; padding: .55mm 1mm; line-height: 1.15; }
.resultados thead th { background: #d9d9d9; text-align: center; font-size: 7pt; }
.resultados .nombre { width: 47%; }.resultados .valor { width: 16%; text-align: center; }.resultados .unidad { width: 13%; text-align: center; }.resultados .referencia { width: 24%; text-align: center; }
.subtitulo td { background: #eeeeee; font-weight: bold; }.firma { position: absolute; bottom: 0; left: 35%; width: 30%; text-align: center; }.firma img { display: block; margin: 0 auto 1mm; max-width: 45mm; max-height: 22mm; }.firma-texto { border-top: .25mm solid #222; padding-top: 1.5mm; }
</style></head><body>
@foreach($resultados as $resultado)
<div class="resultado-paciente">
    <h1>RESULTADOS DE LABORATORIO</h1>
    <table class="datos"><tr><td class="etiqueta">PACIENTE:</td><td colspan="3">{{ $resultado->nombres_apellidos }}</td></tr><tr><td class="etiqueta">DNI:</td><td>{{ $resultado->dni }}</td><td class="etiqueta">FECHA:</td><td>{{ $resultado->fecha_resultado->format('d/m/Y') }}</td></tr><tr><td class="etiqueta">PROCEDENCIA:</td><td colspan="3">{{ $resultado->procedencia }}</td></tr></table>
    @foreach($analisis as $area => $items)
        <h2>{{ $area }}</h2>
        <table class="resultados"><thead><tr><th class="nombre">ANÁLISIS</th><th class="valor">RESULTADO</th><th class="unidad">UNIDAD</th><th class="referencia">VALORES DE REFERENCIA</th></tr></thead><tbody>
        @foreach($items as $item)
            @if(!empty($item['encabezado']))<tr class="subtitulo"><td colspan="4">{{ $item['nombre'] }}</td></tr>
            @else<tr><td>{{ $item['nombre'] }}</td><td class="valor">{{ $resultado->{$item['campo']} }}</td><td class="unidad">{{ $item['unidad'] }}</td><td class="referencia">{{ $item['referencia'] }}</td></tr>@endif
        @endforeach
        </tbody></table>
    @endforeach
    <div class="firma">
        @if($sello = $resultado->selloDigitalDataUri())<img src="{{ $sello }}" alt="Sello digital">@endif
        <div class="firma-texto">Firma y sello</div>
    </div>
</div>
@endforeach
</body></html>
