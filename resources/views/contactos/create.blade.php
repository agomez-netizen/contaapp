@extends('layouts.app')

@section('title', 'Nuevo Contacto')

@section('content')
<div class="container py-4">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold mb-0">➕ Nuevo Contacto</h3>
    <a href="{{ route('contactos.index') }}" class="btn btn-outline-secondary">← Volver</a>
  </div>

  @if($errors->any())
    <div class="alert alert-danger">
      <strong>Corrige los errores:</strong>
      <ul class="mb-0">
        @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
      </ul>
    </div>
  @endif

  <div class="card border-0 shadow-sm">
    <div class="card-body">
      <form method="POST" action="{{ route('contactos.store') }}">
        @csrf

        @include('contactos.form', [
          'contacto' => null,
          'proyectos' => $proyectos,
          'tipos' => $tipos,
          'btnText' => 'Guardar'
        ])

      </form>
    </div>
  </div>

</div>
@endsection
