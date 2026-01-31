@extends('layouts.app')
@section('title','Editar Proyecto')

@section('content')
<div class="container py-4">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h3 class="fw-bold mb-0">✏️ Editar Proyecto</h3>
    <a href="{{ route('proyectos.index') }}" class="btn btn-outline-secondary">Volver</a>
  </div>

  <div class="card shadow-sm">
    <div class="card-body">
      <form method="POST" action="{{ route('proyectos.update', $proyecto) }}">
        @method('PUT')
        @include('proyectos._form', ['proyecto' => $proyecto])
        <button class="btn btn-primary">Actualizar</button>
      </form>
    </div>
  </div>
</div>
@endsection
