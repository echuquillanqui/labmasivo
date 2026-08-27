@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Importar resultados de laboratorio</h1>
        <a href="{{ route('resultados-laboratorio.index') }}" class="btn btn-outline-secondary">Volver</a>
    </div>
    <div class="card shadow-sm"><div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger"><strong>No se pudo importar.</strong><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
        @endif
        <form method="POST" action="{{ route('resultados-laboratorio.importar.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <div class="col-md-5"><label for="archivo" class="form-label">Archivo Excel</label><input id="archivo" name="archivo" type="file" accept=".xls,.xlsx" class="form-control" required><div class="form-text">Se importará únicamente la hoja “PARA LABORATORIO”.</div></div>
                <div class="col-md-3"><label for="fecha_resultado" class="form-label">Fecha del resultado</label><input id="fecha_resultado" name="fecha_resultado" type="date" value="{{ old('fecha_resultado') }}" class="form-control" required></div>
                <div class="col-md-4"><label for="procedencia" class="form-label">Procedencia</label><input id="procedencia" name="procedencia" maxlength="150" value="{{ old('procedencia') }}" class="form-control" required></div>
            </div>
            <button class="btn btn-primary mt-4" type="submit">Importar resultados</button>
        </form>
    </div></div>
</div>
@endsection
