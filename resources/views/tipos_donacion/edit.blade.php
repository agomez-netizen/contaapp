@extends('layouts.app')
@section('title','Editar Tipo de Donación')

@section('content')
<div class="container py-4">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h3 class="fw-bold mb-0">✏️ Editar Tipo</h3>
    <a href="{{ route('tipos_donacion.index') }}" class="btn btn-outline-secondary">Volver</a>
  </div>

  <div class="card shadow-sm">
    <div class="card-body">
      <form method="POST" action="{{ route('tipos_donacion.update', $tipo) }}">
        @method('PUT')
        @include('tipos_donacion._form', ['tipo' => $tipo])
        <button class="btn btn-primary">Actualizar</button>
      </form>
    </div>
  </div>
</div>
@endsection
