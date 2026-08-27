@extends('layouts.app')
@section('content')
<div class="container"><div class="d-flex justify-content-between align-items-center mb-3"><h1 class="h3 mb-0">Nuevo usuario</h1><a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary">Volver</a></div>
<div class="card shadow-sm"><div class="card-body"><form method="POST" action="{{ route('usuarios.store') }}" enctype="multipart/form-data">@csrf @include('usuarios._form')<button class="btn btn-primary mt-4">Crear usuario</button></form></div></div></div>
@endsection
