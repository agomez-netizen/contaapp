@extends('layouts.app')
@section('title','Nuevo Proyecto')

@section('content')
<div class="container py-4">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h3 class="fw-bold mb-0">➕ Nuevo Proyecto</h3>
    <a href="{{ route('proyectos.index') }}" class="btn btn-outline-secondary">Volver</a>
  </div>

  <div class="card shadow-sm">
    <div class="card-body">
      <form method="POST" action="{{ route('proyectos.store') }}">
        @include('proyectos._form')
        <button class="btn btn-primary">Guardar</button>
      </form>
    </div>
  </div>
</div>
@endsection
