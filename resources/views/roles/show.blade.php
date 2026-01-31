@extends('layouts.app')
@section('title','Detalle Rol')

@section('content')
<div class="container py-4">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <div>
      <h3 class="fw-bold mb-0">👁️ Rol</h3>
      <!--<div class="text-muted">ID: {{ $rol->id_rol }}</div>-->
    </div>

    <div class="d-flex gap-2">
      <a href="{{ route('roles.edit', $rol->id_rol) }}" class="btn btn-outline-warning">Editar</a>
      <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary">Volver</a>
    </div>
  </div>

  <div class="card shadow-sm">
    <div class="card-body">
      <div class="mb-2">
        <span class="text-muted">Nombre:</span>
        <strong>{{ $rol->nombre }}</strong>
      </div>

      <div class="mb-2">
        <span class="text-muted">Usuarios con este rol:</span>
        <strong>{{ $rol->usuarios_count ?? 0 }}</strong>
      </div>

      <div class="mt-3">
        <div class="text-muted mb-1">Descripción:</div>
        <div>{{ $rol->descripcion ?: '—' }}</div>
      </div>
    </div>
  </div>
</div>
@endsection
