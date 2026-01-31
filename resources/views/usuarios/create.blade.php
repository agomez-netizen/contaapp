@extends('layouts.app')
@section('title','Nuevo Usuario')

@section('content')
<div class="container py-4">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h3 class="fw-bold mb-0">➕ Nuevo Usuario</h3>
    <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary">Volver</a>
  </div>

  <div class="card shadow-sm">
    <div class="card-body">
      <form method="POST" action="{{ route('usuarios.store') }}">
        @include('usuarios._form')
        <div class="mt-3">
          <button class="btn btn-primary">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
