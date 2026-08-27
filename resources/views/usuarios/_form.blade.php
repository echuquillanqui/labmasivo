<div class="row g-3">
    <div class="col-md-6">
        <label for="name" class="form-label">Nombre</label>
        <input id="name" name="name" value="{{ old('name', $usuario->name ?? '') }}" maxlength="255" class="form-control @error('name') is-invalid @enderror" required autofocus>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label for="email" class="form-label">Correo electrónico</label>
        <input id="email" name="email" type="email" value="{{ old('email', $usuario->email ?? '') }}" maxlength="255" class="form-control @error('email') is-invalid @enderror" required>
        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label for="password" class="form-label">Contraseña</label>
        <input id="password" name="password" type="password" class="form-control @error('password') is-invalid @enderror" {{ isset($usuario) ? '' : 'required' }} autocomplete="new-password">
        <div class="form-text">Mínimo 8 caracteres{{ isset($usuario) ? '; déjala vacía para conservar la actual' : '' }}.</div>
        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label for="password_confirmation" class="form-label">Confirmar contraseña</label>
        <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" {{ isset($usuario) ? '' : 'required' }} autocomplete="new-password">
    </div>
</div>
