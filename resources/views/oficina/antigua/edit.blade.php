@extends('layouts.app')

@section('title', 'Editar Contacto')

@section('content')
<div class="container py-4">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold mb-0">✏️ Editar Contacto</h3>
    <a href="{{ route('contactos.show', $contacto->id_contacto) }}" class="btn btn-outline-secondary">← Volver</a>
  </div>

  @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
  @endif

  @if($errors->any())
    <div class="alert alert-danger">
      <strong>Corrige los errores:</strong>
      <ul class="mb-0">
        @foreach($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="card border-0 shadow-sm">
    <div class="card-body">
      <form method="POST" action="{{ route('contactos.update', $contacto->id_contacto) }}">
        @csrf
        @method('PUT')

        {{-- Formulario reutilizable (incluye Email y Sitio Web en el partial) --}}
        @include('contactos.partials.form', [
          'contacto'  => $contacto,
          'proyectos' => $proyectos,
          'tipos'     => $tipos,
          'btnText'   => 'Actualizar'
        ])

      </form>
    </div>
  </div>

</div>
@endsection
