@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Usuarios</h1>
        <a href="{{ route('usuarios.create') }}" class="btn btn-primary">Nuevo usuario</a>
    </div>
    @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if (session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
    <div class="card shadow-sm"><div class="table-responsive"><table class="table table-striped align-middle mb-0">
        <thead><tr><th>Nombre</th><th>Correo electrónico</th><th>Creado</th><th class="text-end">Acciones</th></tr></thead>
        <tbody>@forelse ($usuarios as $usuario)<tr>
            <td>{{ $usuario->name }} @if(auth()->user()->is($usuario))<span class="badge text-bg-secondary">Tú</span>@endif</td>
            <td>{{ $usuario->email }}</td><td>{{ $usuario->created_at->format('d/m/Y') }}</td>
            <td class="text-end text-nowrap"><a href="{{ route('usuarios.edit', $usuario) }}" class="btn btn-outline-primary btn-sm">Editar</a>
                @unless(auth()->user()->is($usuario))<form class="d-inline" method="POST" action="{{ route('usuarios.destroy', $usuario) }}" onsubmit="return confirm('¿Eliminar este usuario?')">@csrf @method('DELETE')<button class="btn btn-outline-danger btn-sm">Eliminar</button></form>@endunless
            </td>
        </tr>@empty<tr><td colspan="4" class="text-center text-muted py-4">No hay usuarios.</td></tr>@endforelse</tbody>
    </table></div></div>
    <div class="mt-3">{{ $usuarios->links() }}</div>
</div>
@endsection
