@extends('layouts.app')
@section('title','Detalle Tipo de Donación')

@section('content')
<div class="container py-4">
  <div class="d-flex flex-wrap gap-2 align-items-center justify-content-between mb-3">
    <div>
      <h3 class="fw-bold mb-0">👁️ Tipo de Donación</h3>
      <div class="text-muted">ID: {{ $tipo->id_tipo_donacion }}</div>
    </div>
    <div class="d-flex gap-2">
      <a href="{{ route('tipos_donacion.edit',$tipo) }}" class="btn btn-outline-warning">Editar</a>
      <a href="{{ route('tipos_donacion.index') }}" class="btn btn-outline-secondary">Volver</a>
    </div>
  </div>

  <div class="card shadow-sm">
    <div class="card-body">
      <div class="mb-2"><span class="text-muted">Nombre:</span> <strong>{{ $tipo->nombre }}</strong></div>
      <div class="mb-2"><span class="text-muted">Activo:</span>
        @if($tipo->activo) <span class="badge bg-success">Sí</span>
        @else <span class="badge bg-secondary">No</span> @endif
      </div>
      <div class="mt-3">
        <div class="text-muted mb-1">Descripción:</div>
        <div>{{ $tipo->descripcion ?: '—' }}</div>
      </div>
    </div>
  </div>
</div>
@endsection
