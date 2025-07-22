@csrf

<div class="mb-3">
    <label for="rut" class="form-label">Usuario</label>
    <select name="rut" id="rut" class="form-select select2" required>
        <option value="">Seleccione un usuario</option>
        @foreach ($usuarios as $usuario)
            <option value="{{ $usuario->rut }}"
                {{ old('rut', $permiso->rut ?? '') == $usuario->rut ? 'selected' : '' }}>
                {{ $usuario->nombres }} {{ $usuario->paterno }} {{ $usuario->materno }} - {{ $usuario->rut }}
            </option>
        @endforeach
    </select>
    @error('rut') <div class="text-danger">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label for="id_carrera" class="form-label">Carrera</label>
    <select name="id_carrera" id="id_carrera" class="form-select" required>
        <option value="">Seleccione una carrera</option>
        @foreach ($carreras as $carrera)
            <option value="{{ $carrera->id_carrera }}"
                {{ old('id_carrera', $permiso->id_carrera ?? '') == $carrera->id_carrera ? 'selected' : '' }}>
                {{ $carrera->nombre }}
            </option>
        @endforeach
    </select>
    @error('id_carrera') <div class="text-danger">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label for="tipo" class="form-label">Tipo de Permiso</label>
    <select name="tipo" id="tipo" class="form-select" required>
        <option value="">Seleccione un tipo</option>
        @foreach (['Admin', 'Estudiante', 'Docente', 'Coordinador'] as $tipo)
            <option value="{{ $tipo }}"
                {{ old('tipo', $permiso->tipo ?? '') == $tipo ? 'selected' : '' }}>
                {{ $tipo }}
            </option>
        @endforeach
    </select>
    @error('tipo') <div class="text-danger">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label for="estado" class="form-label">Estado</label>
    <select name="estado" id="estado" class="form-select" required>
        <option value="1" {{ old('estado', $permiso->estado ?? '') == '1' ? 'selected' : '' }}>Activo</option>
        <option value="0" {{ old('estado', $permiso->estado ?? '') == '0' ? 'selected' : '' }}>Inactivo</option>
    </select>
    @error('estado') <div class="text-danger">{{ $message }}</div> @enderror
</div>
