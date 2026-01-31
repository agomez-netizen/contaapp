@extends('layouts.app')
@section('title','Nueva Ubicación')

@section('content')
<div class="container py-4">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h3 class="fw-bold mb-0"><i class="bi bi-plus-circle me-1"></i> Nueva Ubicación</h3>
    <a href="{{ route('ubicaciones.index') }}" class="btn btn-outline-secondary">
      <i class="bi bi-arrow-left"></i> Volver
    </a>
  </div>

  <div class="card shadow-sm">
    <div class="card-body">
      <form method="POST" action="{{ route('ubicaciones.store') }}" class="row g-3">
        @csrf

        <div class="col-md-8">
          <label class="form-label">Nombre</label>
          <input name="nombre" value="{{ old('nombre') }}"
                 class="form-control @error('nombre') is-invalid @enderror" required maxlength="80">
          @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-4 d-flex align-items-end">
          <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="activo" value="1" id="activo"
                   {{ old('activo', 1) ? 'checked' : '' }}>
            <label class="form-check-label" for="activo">Activo</label>
          </div>
        </div>

        <div class="col-12 d-flex gap-2">
          <button class="btn btn-primary">
            <i class="bi bi-check2-circle me-1"></i> Guardar
          </button>
          <a href="{{ route('ubicaciones.index') }}" class="btn btn-outline-secondary">Cancelar</a>
        </div>

      </form>
    </div>
  </div>
</div>
@endsection
