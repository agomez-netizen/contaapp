@extends('layouts.app')
@section('title','Detalle Ubicación')

@section('content')
<div class="container py-4">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <div>
      <h3 class="fw-bold mb-0"><i class="bi bi-geo me-1"></i> Detalle</h3>
      <div class="text-muted">{{ $ubicacion->nombre }}</div>
    </div>
    <div class="d-flex gap-2">
      <a href="{{ route('ubicaciones.edit',$ubicacion) }}" class="btn btn-outline-warning" title="Editar">
        <i class="bi bi-pencil"></i>
      </a>
      <a href="{{ route('ubicaciones.index') }}" class="btn btn-outline-secondary" title="Volver">
        <i class="bi bi-arrow-left"></i>
      </a>
    </div>
  </div>

  <div class="card shadow-sm">
    <div class="card-body">
      <div class="mb-2"><span class="text-muted">Nombre:</span> <span class="fw-semibold">{{ $ubicacion->nombre }}</span></div>
      <div class="mb-2">
        <span class="text-muted">Estado:</span>
        @if($ubicacion->activo)
          <span class="badge text-bg-success">Activo</span>
        @else
          <span class="badge text-bg-secondary">Inactivo</span>
        @endif
      </div>
      <div class="mb-2"><span class="text-muted">Creado:</span> {{ optional($ubicacion->created_at)->format('Y-m-d H:i') }}</div>
      <div class="mb-2"><span class="text-muted">Actualizado:</span> {{ optional($ubicacion->updated_at)->format('Y-m-d H:i') }}</div>
    </div>
  </div>
</div>
@endsection
