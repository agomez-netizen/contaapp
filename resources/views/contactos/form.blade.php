{{--
  Partial de Contactos
  Variables esperadas:
  - $contacto (opcional)
  - $proyectos (collection)
  - $tipos (array)
  - $btnText (string)
--}}

@php
  // Normaliza variable por si algún view la pasa como $row
  $c = $contacto ?? ($row ?? null);
@endphp

<div class="row g-3">

  <div class="col-md-6">
    <label class="form-label">Proyecto *</label>
    <select class="form-select" name="id_proyecto" required>
      <option value="">— Selecciona —</option>
      @foreach($proyectos as $p)
        <option value="{{ $p->id_proyecto }}"
          {{ (string) old('id_proyecto', $c->id_proyecto ?? '') === (string) $p->id_proyecto ? 'selected' : '' }}>
          {{ $p->nombre }}
        </option>
      @endforeach
    </select>
  </div>

  <div class="col-md-6">
    <label class="form-label">Tipo *</label>
    <select class="form-select" name="tipo" required>
      <option value="">— Selecciona —</option>
      @foreach($tipos as $t)
        <option value="{{ $t }}" {{ old('tipo', $c->tipo ?? '') === $t ? 'selected' : '' }}>
          {{ $t }}
        </option>
      @endforeach
    </select>
  </div>

  <div class="col-md-6">
    <label class="form-label">Nombre *</label>
    <input class="form-control" name="nombre" value="{{ old('nombre', $c->nombre ?? '') }}" required maxlength="150">
  </div>

  <div class="col-md-3">
    <label class="form-label">Teléfono</label>
    <input class="form-control" name="telefono" value="{{ old('telefono', $c->telefono ?? '') }}" maxlength="30">
  </div>

  <div class="col-md-3">
    <label class="form-label">Extensión</label>
    <input class="form-control" name="extension" value="{{ old('extension', $c->extension ?? '') }}" maxlength="10">
  </div>

  <div class="col-md-6">
    <label class="form-label">Correo</label>
    <input type="email" class="form-control" name="correo" value="{{ old('correo', $c->correo ?? '') }}" maxlength="120">
  </div>


  <div class="col-md-6">
    <label class="form-label">Sitio Web</label>
    <input type="url" class="form-control" name="sitio_web" placeholder="https://ejemplo.com"
           value="{{ old('sitio_web', $c->sitio_web ?? '') }}" maxlength="150">
  </div>

  <div class="col-md-6">
    <label class="form-label">NIT</label>
    <input class="form-control" name="nit" value="{{ old('nit', $c->nit ?? '') }}" maxlength="30">
  </div>

  <div class="col-12">
    <label class="form-label">Dirección</label>
    <input class="form-control" name="direccion" value="{{ old('direccion', $c->direccion ?? '') }}" maxlength="255">
  </div>

  <div class="col-12">
    <label class="form-label">Motivo</label>
    <input class="form-control" name="motivo" value="{{ old('motivo', $c->motivo ?? '') }}" maxlength="255">
  </div>

  <div class="col-12 d-grid d-md-flex justify-content-md-end mt-2">
    <button class="btn btn-primary px-4">{{ $btnText ?? 'Guardar' }}</button>
  </div>

</div>
