@extends('layouts.app')

@section('title', 'Detalle Contacto')

@section('content')
<div class="container py-4">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h3 class="fw-bold mb-0">👤 {{ $contacto->nombre }}</h3>
      <div class="text-muted">
        {{ $contacto->tipo }} • {{ $contacto->proyecto->nombre ?? ('Proyecto ID '.$contacto->id_proyecto) }}
      </div>
    </div>

    <div class="d-flex gap-2">
      <a href="{{ route('contactos.index') }}" class="btn btn-outline-secondary">← Volver</a>
      <a href="{{ route('contactos.edit', $contacto->id_contacto) }}" class="btn btn-primary">Editar</a>
      <form action="{{ route('contactos.destroy', $contacto->id_contacto) }}" method="POST"
            onsubmit="return confirm('¿Eliminar este contacto?');">
        @csrf
        @method('DELETE')
        <button class="btn btn-outline-danger">Eliminar</button>
      </form>
    </div>
  </div>

  @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
  @endif

  <div class="card border-0 shadow-sm">
    <div class="card-body">
      <div class="row g-3">
        <div class="col-md-6">
          <div class="text-muted">Teléfono</div>
          <div class="fw-semibold">{{ $contacto->telefono ?? '—' }}</div>
        </div>

        <div class="col-md-6">
          <div class="text-muted">Extensión</div>
          <div class="fw-semibold">{{ $contacto->extension ?? '—' }}</div>
        </div>

        <div class="col-md-6">
          <div class="text-muted">Correo</div>
          <div class="fw-semibold">{{ $contacto->correo ?? '—' }}</div>
        </div>


        <div class="col-md-6">
            <div class="text-muted">Sitio Web</div>
            <div class="fw-semibold">
                @if($contacto->sitio_web)
                <a href="{{ $contacto->sitio_web }}" target="_blank">
                    {{ $contacto->sitio_web }}
                </a>
                @else
                —
                @endif
            </div>
        </div>

        <div class="col-md-6">
          <div class="text-muted">NIT</div>
          <div class="fw-semibold">{{ $contacto->nit ?? '—' }}</div>
        </div>

        <div class="col-12">
          <div class="text-muted">Dirección</div>
          <div class="fw-semibold">{{ $contacto->direccion ?? '—' }}</div>
        </div>

        <div class="col-12">
          <div class="text-muted">Notas</div>
          <div class="fw-semibold">{{ $contacto->motivo ?? '—' }}</div>
        </div>
      </div>
    </div>
  </div>

</div>
@endsection
