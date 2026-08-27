@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <h1 class="h3 mb-0">Resultados de laboratorio</h1>
        <a class="btn btn-primary" href="{{ route('resultados-laboratorio.importar.create') }}">Importar resultados</a>
    </div>
    @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if ($resumen = session('resumen_importacion'))
        <div class="card border-success mb-3"><div class="card-header fw-bold">Resumen de importación</div><div class="card-body">
            <div class="row g-2 small">
                <div class="col-md-4"><strong>Archivo:</strong> {{ $resumen['archivo_nombre'] }}</div><div class="col-md-8"><strong>Lote:</strong> <code>{{ $resumen['lote_uuid'] }}</code></div>
                <div class="col-md-3"><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($resumen['fecha_resultado'])->format('d/m/Y') }}</div><div class="col-md-3"><strong>Procedencia:</strong> {{ $resumen['procedencia'] }}</div>
                <div class="col-md-6">Leídas: <strong>{{ $resumen['filas_leidas'] }}</strong> · Creadas: <strong>{{ $resumen['creadas'] }}</strong> · Actualizadas: <strong>{{ $resumen['actualizadas'] }}</strong> · Con error: <strong>{{ $resumen['errores'] }}</strong></div>
            </div>
        </div>
    @endif
    <form method="GET" class="card card-body mb-3"><div class="row g-2">
        <div class="col-lg-3"><input name="lote_uuid" value="{{ request('lote_uuid') }}" class="form-control" placeholder="UUID del lote"></div>
        <div class="col-lg-2"><input type="date" name="fecha" value="{{ request('fecha') }}" class="form-control"></div>
        <div class="col-lg-2"><input name="procedencia" value="{{ request('procedencia') }}" class="form-control" placeholder="Procedencia"></div>
        <div class="col-lg-2"><input name="dni" value="{{ request('dni') }}" class="form-control" placeholder="DNI"></div>
        <div class="col-lg-2"><input name="nombres" value="{{ request('nombres') }}" class="form-control" placeholder="Nombres y apellidos"></div>
        <div class="col-lg-1 d-grid"><button class="btn btn-outline-primary">Filtrar</button></div>
    </div></form>
    <form method="POST" action="{{ route('resultados-laboratorio.pdf-seleccionados') }}" target="_blank">@csrf
        <div class="d-flex flex-wrap gap-2 mb-2">
            <button type="button" id="seleccionar-todos" class="btn btn-outline-secondary btn-sm">Seleccionar todos</button>
            <button class="btn btn-success btn-sm">Imprimir seleccionados</button>
            @if(request('lote_uuid'))<a target="_blank" class="btn btn-dark btn-sm" href="{{ route('resultados-laboratorio.lote.pdf', request('lote_uuid')) }}">Imprimir todo el lote</a>@endif
        </div>
        <div class="table-responsive"><table class="table table-sm table-striped table-bordered align-middle">
            <thead class="table-secondary"><tr><th><span class="visually-hidden">Seleccionar</span></th><th>DNI</th><th>Nombres y apellidos</th><th>Fecha</th><th>Procedencia</th><th>Archivo</th><th>Acciones</th></tr></thead>
            <tbody>@forelse($resultados as $resultado)<tr>
                <td><input class="form-check-input selector" type="checkbox" name="resultados[]" value="{{ $resultado->id }}"></td><td>{{ $resultado->dni }}</td><td>{{ $resultado->nombres_apellidos }}</td><td>{{ $resultado->fecha_resultado->format('d/m/Y') }}</td><td>{{ $resultado->procedencia }}</td><td>{{ $resultado->archivo_nombre }}</td>
                <td class="text-nowrap"><a class="btn btn-outline-primary btn-sm" href="{{ route('resultados-laboratorio.show', $resultado) }}">Ver</a> <a target="_blank" class="btn btn-outline-dark btn-sm" href="{{ route('resultados-laboratorio.pdf', $resultado) }}">Imprimir</a></td>
            </tr>@empty<tr><td colspan="7" class="text-center py-4 text-muted">No hay resultados.</td></tr>@endforelse</tbody>
        </table></div>
    </form>
    {{ $resultados->links() }}
</div>
<script>document.getElementById('seleccionar-todos').addEventListener('click', function () { const items = [...document.querySelectorAll('.selector')]; const marcar = items.some(item => !item.checked); items.forEach(item => item.checked = marcar); this.textContent = marcar ? 'Deseleccionar todos' : 'Seleccionar todos'; });</script>
@endsection
