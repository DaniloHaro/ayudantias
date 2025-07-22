
<div class="mb-3">
    <label for="n_matricula" class="form-label">Número Matrícula</label>
    <input type="text" name="n_matricula" id="n_matricula" class="form-control" value="{{ old('n_matricula', $solicitud->n_matricula ?? '') }}">
</div>

<div class="mb-3">
    <label for="id_asigantura" class="form-label">Asignatura</label>
    <select name="id_asigantura" id="id_asigantura" class="form-select" required>
        <option value="">Seleccione una asignatura</option>
        @foreach($asignaturas as $asignatura)
            <option value="{{ $asignatura->id_asigantura }}" {{ old('id_asigantura', $solicitud->id_asigantura ?? '') == $asignatura->id_asigantura ? 'selected' : '' }}>
                {{ $asignatura->nombre }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label for="id_carrera_estudiante" class="form-label">Carrera Estudiante</label>
    <select name="id_carrera_estudiante" id="id_carrera_estudiante" class="form-select" required>
        <option value="">Seleccione una carrera</option>
        @foreach($carreras as $carrera)
            <option value="{{ $carrera->id_carrera }}" {{ old('id_carrera_estudiante', $solicitud->id_carrera_estudiante ?? '') == $carrera->id_carrera ? 'selected' : '' }}>
                {{ $carrera->nombre }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label for="datos_banco_id" class="form-label">Datos Banco</label>
    <select name="datos_banco_id" id="datos_banco_id" class="form-select" required>
        <option value="">Seleccione datos bancarios</option>
        @foreach($datosBanco as $datos)
            <option value="{{ $datos->id }}" {{ old('datos_banco_id', $solicitud->datos_banco_id ?? '') == $datos->id ? 'selected' : '' }}>
                {{ $datos->banco->nombre }} - {{ $datos->tipo_cuenta }}: {{ $datos->numero_cuenta }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label for="pers_dig" class="form-label">Persona Digna</label>
    <input type="text" name="pers_dig" id="pers_dig" class="form-control" value="{{ old('pers_dig', $solicitud->pers_dig ?? '') }}" required>
</div>

<div class="mb-3">
    <label for="fecha_dig" class="form-label">Fecha de Dig</label>
    <input type="datetime-local" name="fecha_dig" id="fecha_dig" class="form-control" value="{{ old('fecha_dig', optional($solicitud)->fecha_dig ? $solicitud->fecha_dig->format('Y-m-d\TH:i') : '') }}" required>
</div>

<div class="mb-3">
    <label for="estado" class="form-label">Estado</label>
    <select name="estado" id="estado" class="form-select">
        <option value="1" {{ old('estado', $solicitud->estado ?? 1) == 1 ? 'selected' : '' }}>Activo</option>
        <option value="0" {{ old('estado', $solicitud->estado ?? 1) == 0 ? 'selected' : '' }}>Inactivo</option>
    </select>
</div>
