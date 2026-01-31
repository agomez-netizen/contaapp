@extends('layouts.app')
@section('title','Detalle Usuario')

@section('content')
<div class="container py-4">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <div>
      <h3 class="fw-bold mb-0">👁️ Usuario</h3>
      <div class="text-muted">ID: {{ $usuario->id_usuario }}</div>
    </div>
    <div class="d-flex gap-2">
      <a href="{{ route('usuarios.edit',$usuario) }}" class="btn btn-outline-warning">Editar</a>
      <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary">Volver</a>
    </div>
  </div>

  <div class="card shadow-sm">
    <div class="card-body">
      <div class="mb-2"><span class="text-muted">Nombre:</span> <strong>{{ $usuario->nombre }} {{ $usuario->apellido }}</strong></div>
      <div class="mb-2"><span class="text-muted">Usuario:</span> <strong>{{ $usuario->usuario }}</strong></div>
      <div class="mb-2"><span class="text-muted">Rol:</span> <strong>{{ $usuario->rol->nombre ?? '—' }}</strong></div>
      <div class="mb-2"><span class="text-muted">Estado:</span>
        @if($usuario->estado) <span class="badge bg-success">Activo</span>
        @else <span class="badge bg-secondary">Inactivo</span> @endif
      </div>
    </div>
  </div>
</div>
@endsection
