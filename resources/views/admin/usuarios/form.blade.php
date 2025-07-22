@csrf

{{-- RUT (obligatorio) --}}
<div class="mb-3">
    <label for="rut" class="form-label">
        RUT <span class="text-danger" title="Campo obligatorio">*</span>
    </label>
    <input type="text" name="rut" id="rut" maxlength="12"
           class="form-control @error('rut') is-invalid @enderror"
           value="{{ old('rut', ($usuario->rut) ?? '') }}"
           {{ isset($usuario) ? 'readonly' : '' }}
           placeholder="Ej: 12.345.678-9"
           required>
    @error('rut')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

{{-- Nombres (obligatorio) --}}
<div class="mb-3">
    <label for="nombres" class="form-label">
        Nombres <span class="text-danger" title="Campo obligatorio">*</span>
    </label>
    <input type="text" name="nombres" id="nombres"
           class="form-control @error('nombres') is-invalid @enderror"
           value="{{ old('nombres', $usuario->nombres ?? '') }}"
           required>
    @error('nombres')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

{{-- Apellido Paterno (obligatorio) --}}
<div class="mb-3">
    <label for="paterno" class="form-label">
        Apellido Paterno <span class="text-danger" title="Campo obligatorio">*</span>
    </label>
    <input type="text" name="paterno" id="paterno"
           class="form-control @error('paterno') is-invalid @enderror"
           value="{{ old('paterno', $usuario->paterno ?? '') }}"
           required>
    @error('paterno')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

{{-- Apellido Materno --}}
<div class="mb-3">
    <label for="materno" class="form-label">Apellido Materno</label>
    <input type="text" name="materno" id="materno"
           class="form-control @error('materno') is-invalid @enderror"
           value="{{ old('materno', $usuario->materno ?? '') }}">
    @error('materno')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

{{-- Email (opcional) --}}
<div class="mb-3">
    <label for="email" class="form-label">Correo Electrónico</label>
    <input type="email" name="email" id="email"
           class="form-control @error('email') is-invalid @enderror"
           value="{{ old('email', $usuario->email ?? '') }}"
           placeholder="ejemplo@dominio.com">
    @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

{{-- Sexo Registral --}}
<div class="mb-3">
    <label for="sexo_registral" class="form-label">Sexo Registral</label>
    <input type="text" name="sexo_registral" id="sexo_registral"
           class="form-control @error('sexo_registral') is-invalid @enderror"
           value="{{ old('sexo_registral', $usuario->sexo_registral ?? '') }}">
    @error('sexo_registral')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

{{-- Género --}}
<div class="mb-3">
    <label for="genero" class="form-label">Género</label>
    <input type="text" name="genero" id="genero"
           class="form-control @error('genero') is-invalid @enderror"
           value="{{ old('genero', $usuario->genero ?? '') }}">
    @error('genero')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

{{-- Cuenta/Pasaporte --}}
<div class="mb-3">
    <label for="cuenta_pasaporte" class="form-label">Cuenta/Pasaporte</label>
    <input type="text" name="cuenta_pasaporte" id="cuenta_pasaporte"
           class="form-control @error('cuenta_pasaporte') is-invalid @enderror"
           value="{{ old('cuenta_pasaporte', $usuario->cuenta_pasaporte ?? '') }}">
    @error('cuenta_pasaporte')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

{{-- Estado (obligatorio) --}}
<div class="mb-3">
    <label for="estado" class="form-label">
        Estado <span class="text-danger" title="Campo obligatorio">*</span>
    </label>
    <select name="estado" id="estado"
            class="form-select @error('estado') is-invalid @enderror" required>
        <option value="">-- Seleccione Estado --</option>
        <option value="1" {{ old('estado', $usuario->estado ?? '') == 1 ? 'selected' : '' }}>Activo</option>
        <option value="0" {{ old('estado', $usuario->estado ?? '') == 0 ? 'selected' : '' }}>Inactivo</option>
    </select>
    @error('estado')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

{{-- Botón enviar --}}
<div class="text-end">
    <button type="submit" class="btn btn-primary">
        {{ isset($usuario) ? 'Actualizar Usuario' : 'Crear Usuario' }}
    </button>
</div>

