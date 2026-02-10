<div class="row g-3">

  <div class="col-md-6">
    <label class="form-label">Proyecto *</label>
    <select class="form-select" name="id_proyecto" required>
      <option value="">— Selecciona —</option>
      @foreach($proyectos as $p)
        <option value="{{ $p->id_proyecto }}"
          {{ old('id_proyecto', $contacto->id_proyecto ?? '') == $p->id_proyecto ? 'selected' : '' }}>
          {{ $p->nombre }}
        </option>
      @endforeach
    </select>
  </div>

  <div class="col-md-6">
    <label class="form-label">Tipo *</label>
    <select class="form-select" name="tipo" required>
      @foreach($tipos as $t)
        <option value="{{ $t }}" {{ old('tipo', $contacto->tipo ?? '') === $t ? 'selected' : '' }}>
          {{ $t }}
        </option>
      @endforeach
    </select>
  </div>

  <div class="col-md-6">
    <label class="form-label">Nombre *</label>
    <input class="form-control" name="nombre" value="{{ old('nombre', $contacto->nombre ?? '') }}" required maxlength="150">
  </div>

  <div class="col-md-3">
    <label class="form-label">Teléfono</label>
    <input class="form-control" name="telefono" value="{{ old('telefono', $contacto->telefono ?? '') }}" maxlength="30">
  </div>

  <div class="col-md-3">
    <label class="form-label">Extensión</label>
    <input class="form-control" name="extension" value="{{ old('extension', $contacto->extension ?? '') }}" maxlength="10">
  </div>

  <div class="col-md-6">
    <label class="form-label">Correo</label>
    <input type="email" class="form-control" name="correo" value="{{ old('correo', $contacto->correo ?? '') }}" maxlength="120">
  </div>

  <div class="col-md-6">
    <label class="form-label">NIT</label>
    <input class="form-control" name="nit" value="{{ old('nit', $contacto->nit ?? '') }}" maxlength="30">
  </div>

  <div class="col-12">
    <label class="form-label">Dirección</label>
    <input class="form-control" name="direccion" value="{{ old('direccion', $contacto->direccion ?? '') }}" maxlength="255">
  </div>

  <div class="col-12">
    <label class="form-label">Motivo</label>
    <input class="form-control" name="motivo" value="{{ old('motivo', $contacto->motivo ?? '') }}" maxlength="255">
  </div>

  <div class="col-12 d-grid d-md-flex justify-content-md-end mt-2">
    <button class="btn btn-primary px-4">{{ $btnText }}</button>
  </div>

</div>
