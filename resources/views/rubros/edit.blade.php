@extends('layouts.app')

@section('title', 'Editar Rubro')

@section('content')
<div class="container py-4">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold mb-0">✏️ Editar Rubro</h3>
    <a href="{{ route('rubros.index') }}" class="btn btn-outline-secondary">← Volver</a>
  </div>

  @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <strong>Corrige los errores:</strong>
      <ul class="mb-0">
        @foreach($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
  @endif

  <div class="card border-0 shadow-sm">
    <div class="card-body">
      <form method="POST" action="{{ route('rubros.update', $rubro->id_rubro) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
          <label class="form-label">Nombre</label>
          <input type="text" name="nombre" value="{{ old('nombre', $rubro->nombre) }}" class="form-control" required maxlength="255">
        </div>

        <div class="form-check mb-3">
          <input class="form-check-input" type="checkbox" name="activo" value="1" id="activo"
                 {{ old('activo', $rubro->activo ? '1' : '0') == '1' ? 'checked' : '' }}>
          <label class="form-check-label" for="activo">
            Activo
          </label>
        </div>

        <div class="d-flex gap-2">
          <button class="btn btn-primary" type="submit">Actualizar</button>
          <a class="btn btn-outline-secondary" href="{{ route('rubros.index') }}">Cancelar</a>
        </div>
      </form>
    </div>
  </div>

</div>
@endsection
