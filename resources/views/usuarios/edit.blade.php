@extends('layouts.app')
@section('title','Editar Usuario')

@section('content')
<div class="container py-4">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h3 class="fw-bold mb-0">✏️ Editar Usuario</h3>
    <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary">Volver</a>
  </div>

  <div class="card shadow-sm">
    <div class="card-body">
      <form method="POST" action="{{ route('usuarios.update', $usuario) }}">
        @method('PUT')
        @include('usuarios._form', ['usuario' => $usuario])
        <div class="mt-3">
          <button class="btn btn-primary">Actualizar</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
