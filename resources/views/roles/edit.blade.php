@extends('layouts.app')
@section('title','Editar Rol')

@section('content')
<div class="container py-4">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h3 class="fw-bold mb-0">✏️ Editar Rol</h3>
    <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary">Volver</a>
  </div>

  <div class="card shadow-sm">
    <div class="card-body">

      <form method="POST" action="{{ route('roles.update', ['role' => $rol->id_rol]) }}">
        @csrf
        @method('PUT')

        @include('roles._form', ['rol' => $rol])

        <button class="btn btn-primary">Actualizar</button>
      </form>

    </div>
  </div>
</div>
@endsection
