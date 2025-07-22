<div class="mb-3">
    <label for="nombre" class="form-label">Nombre</label>
    <input type="text" name="nombre" id="nombre" class="form-control" value="{{ old('nombre', $asignatura->nombre ?? '') }}" required>
</div>

<div class="mb-3">
    <label for="id_carrera" class="form-label">Carrera</label>
    <select name="id_carrera" id="id_carrera" class="form-select" required>
        <option value="">Seleccione una carrera</option>
        @foreach($carreras as $carrera)
            <option value="{{ $carrera->id_carrera }}" {{ old('id_carrera', $asignatura->id_carrera ?? '') == $carrera->id_carrera ? 'selected' : '' }}>
                {{ $carrera->nombre }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label for="rut" class="form-label">Profesor (RUT)</label>
    <select name="rut" id="rut" class="form-select select2" required>
        <option value="">Seleccione un usuario</option>
        @foreach($usuarios as $usuario)
            <option value="{{ $usuario->rut }}" {{ old('rut', $asignatura->rut ?? '') == $usuario->rut ? 'selected' : '' }}>
                {{ $usuario->nombres }} {{ $usuario->paterno }} {{ $usuario->materno }} ({{ $usuario->rut }})
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label for="seccion" class="form-label">Sección</label>
    <input type="text" name="seccion" id="seccion" class="form-control" value="{{ old('seccion', $asignatura->seccion ?? '') }}">
</div>

<div class="mb-3">
    <label for="bloque_1" class="form-label">Bloque 1</label>
    <input type="text" name="bloque_1" id="bloque_1" class="form-control" value="{{ old('bloque_1', $asignatura->bloque_1 ?? '') }}">
</div>

<div class="mb-3">
    <label for="bloque_2" class="form-label">Bloque 2</label>
    <input type="text" name="bloque_2" id="bloque_2" class="form-control" value="{{ old('bloque_2', $asignatura->bloque_2 ?? '') }}">
</div>

<div class="mb-3">
    <label for="bloque_3" class="form-label">Bloque 3</label>
    <input type="text" name="bloque_3" id="bloque_3" class="form-control" value="{{ old('bloque_3', $asignatura->bloque_3 ?? '') }}">
</div>

<div class="mb-3">
    <label for="cupos" class="form-label">Cupos</label>
    <input type="number" name="cupos" id="cupos" class="form-control" value="{{ old('cupos', $asignatura->cupos ?? '') }}">
</div>

<div class="mb-3">
    <label for="estado" class="form-label">Estado</label>
    <select name="estado" id="estado" class="form-select">
        <option value="1" {{ old('estado', $asignatura->estado ?? 1) == 1 ? 'selected' : '' }}>Activo</option>
        <option value="0" {{ old('estado', $asignatura->estado ?? 1) == 0 ? 'selected' : '' }}>Inactivo</option>
    </select>
</div>
