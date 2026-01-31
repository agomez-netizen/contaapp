@extends('layouts.app')

@section('title', 'Crear Medio')

@section('content')
<div class="container py-4">

  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h3 class="fw-bold mb-1">📢 Registro De Medios</h3>
      <div class="text-muted">Formulario De Ingreso De Medios De Comunicación</div>
    </div>

    <a href="{{ route('medios.index') }}" class="btn btn-outline-secondary">
      ← Volver
    </a>
  </div>

  <div class="card border-0 shadow-sm">
    <div class="card-body">

      {{-- Errores --}}
      @if ($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form method="POST" action="{{ route('medios.store') }}">
        @csrf

        <h6 class="fw-semibold mb-3">🗞️ Datos Del Medio</h6>

        <div class="row g-3 mb-4">

          <div class="col-md-6">
            <label class="form-label">Medio</label>
            <input type="text" name="medio" class="form-control" value="{{ old('medio') }}" required>
          </div>

          <div class="col-md-4">
            <label class="form-label">Tipo</label>
            <select name="tipo" class="form-select" required>
              <option value="">Seleccione</option>
              @foreach (['Local','Nacional','Internacional'] as $t)
                <option value="{{ $t }}" {{ old('tipo')===$t ? 'selected' : '' }}>{{ $t }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-md-6">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}" required>
          </div>

          <div class="col-md-4">
            <label class="form-label">Teléfono</label>
            <input type="text" name="telefono" class="form-control" value="{{ old('telefono') }}">
          </div>

          <div class="col-md-4">
            <label class="form-label">Contacto/Cargo</label>
            <input type="text" name="contacto_cargo" class="form-control" value="{{ old('contacto_cargo') }}">
          </div>

          <div class="col-md-4">
            <label class="form-label">Nombre Completo</label>
            <input type="text" name="nombre_completo" class="form-control" value="{{ old('nombre_completo') }}">
          </div>

          <div class="col-md-4">
            <label class="form-label">Celular del Contacto</label>
            <input type="text" name="celular_contacto" class="form-control" value="{{ old('celular_contacto') }}">
          </div>

          <div class="col-md-12">
            <label class="form-label">Dirección</label>
            <input type="text" name="direccion" class="form-control" value="{{ old('direccion') }}">
          </div>

          <div class="col-md-6">
            <label class="form-label">E-mail</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}">
          </div>

          <div class="col-md-6">
            <label class="form-label">Web-site</label>
            <input type="text" name="website" class="form-control" value="{{ old('website') }}" placeholder="https://">
          </div>

          <div class="col-md-6">
            <label class="form-label">Redes Sociales</label>
            <input type="text" name="redsocial" class="form-control" value="{{ old('redsocial') }}" placeholder="https://facebook.com/...">
          </div>

          <div class="col-md-12">
            <label class="form-label">Observaciones</label>
            <textarea name="observaciones" class="form-control" rows="3">{{ old('observaciones') }}</textarea>
          </div>

        </div>

        <div class="d-flex justify-content-end gap-2">
          <button type="reset" class="btn btn-outline-secondary">Limpiar</button>
          <button type="submit" class="btn btn-primary">Guardar Medio</button>
        </div>

      </form>

    </div>
  </div>

</div>
@endsection
