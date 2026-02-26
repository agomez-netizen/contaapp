@extends('layouts.app')
@section('title','Detalle Proyecto')

@section('content')
<div class="container py-4">
  <div class="d-flex flex-wrap gap-2 align-items-center justify-content-between mb-3">
    <div>
      <h3 class="fw-bold mb-0">👁️ Proyecto</h3>
      <div class="text-muted">ID: {{ $proyecto->id_proyecto }}</div>
    </div>
    <div class="d-flex gap-2">
      <a href="{{ route('proyectos.edit',$proyecto) }}" class="btn btn-outline-warning">Editar</a>
      <a href="{{ route('proyectos.index') }}" class="btn btn-outline-secondary">Volver</a>
    </div>
  </div>

  <div class="card shadow-sm">
    <div class="card-body">
      <div class="mb-2"><span class="text-muted">Nombre:</span> <strong>{{ $proyecto->nombre }}</strong></div>
      <div class="mb-2"><span class="text-muted">Activo:</span>
        @if($proyecto->activo) <span class="badge bg-success">Sí</span>
        @else <span class="badge bg-secondary">No</span> @endif
      </div>
      <div class="mt-3">
        <div class="text-muted mb-1">Descripción:</div>
        <div>{{ $proyecto->descripcion ?: '—' }}</div>
        @if($proyecto->direccion)
        <p>
            <strong>URL:</strong>
            <a href="{{ $proyecto->direccion }}" target="_blank">
                {{ $proyecto->direccion }}
            </a>
        </p>
    @endif
      </div>

    </div>
  </div>
</div>
@endsection
