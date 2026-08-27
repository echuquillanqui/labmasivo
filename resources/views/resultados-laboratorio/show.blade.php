@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3"><h1 class="h3 mb-0">Resultado importado</h1><div><a target="_blank" href="{{ route('resultados-laboratorio.pdf', $resultado) }}" class="btn btn-dark">Imprimir</a> <a href="{{ route('resultados-laboratorio.index', ['lote_uuid' => $resultado->lote_uuid]) }}" class="btn btn-outline-secondary">Volver al lote</a></div></div>
    <div class="card"><div class="card-body">
        <dl class="row"><dt class="col-sm-2">Paciente</dt><dd class="col-sm-10">{{ $resultado->nombres_apellidos }}</dd><dt class="col-sm-2">DNI</dt><dd class="col-sm-10">{{ $resultado->dni }}</dd><dt class="col-sm-2">Fecha</dt><dd class="col-sm-10">{{ $resultado->fecha_resultado->format('d/m/Y') }}</dd><dt class="col-sm-2">Procedencia</dt><dd class="col-sm-10">{{ $resultado->procedencia }}</dd><dt class="col-sm-2">Lote</dt><dd class="col-sm-10"><code>{{ $resultado->lote_uuid }}</code></dd></dl>
        @foreach(collect(config('laboratorios.analisis'))->sortBy('orden')->groupBy('area') as $area => $items)
            <h2 class="h5 mt-4">{{ $area }}</h2><div class="table-responsive"><table class="table table-sm table-bordered"><thead class="table-light"><tr><th>Análisis</th><th>Resultado</th><th>Unidad</th><th>Valores de referencia</th></tr></thead><tbody>
            @foreach($items as $item) @if(!empty($item['encabezado']))<tr class="table-light"><th colspan="4">{{ $item['nombre'] }}</th></tr>@else<tr><td>{{ $item['nombre'] }}</td><td>{{ $resultado->{$item['campo']} }}</td><td>{{ $item['unidad'] }}</td><td>{{ $item['referencia'] }}</td></tr>@endif @endforeach
            </tbody></table></div>
        @endforeach
    </div></div>
</div>
@endsection
