@extends('layouts.app')

@section('title', 'Detalle Medio')

@section('content')
@php
  // Helper: muestra '—' si viene null o vacío
  $v = fn($x) => ($x !== null && trim((string)$x) !== '') ? $x : '—';

  $id = $medio->id_medio;
@endphp

<div class="container py-4">

  <div class="card shadow-sm border-0">
    <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
      <h5 class="mb-0">Detalle Medio #{{ $id }}</h5>

      <div class="d-flex gap-2">
        <a href="{{ route('medios.index') }}" class="btn btn-sm btn-light">← Volver</a>
        <a href="{{ route('medios.edit', $id) }}" class="btn btn-sm btn-light">✏️ Editar</a>
      </div>
    </div>

    <div class="card-body">
      <div class="row g-3">

        {{-- ================= DATOS DEL MEDIO ================= --}}
        <div class="col-12">
          <h6 class="text-primary mb-2">Datos del Medio</h6>
          <hr class="mt-0">
        </div>

        <div class="col-md-4">
          <div class="text-muted small">Medio</div>
          <div class="fw-semibold">{{ $v($medio->medio) }}</div>
        </div>

        <div class="col-md-4">
          <div class="text-muted small">Tipo</div>
          <div class="fw-semibold">{{ $v($medio->tipo) }}</div>
        </div>

        <div class="col-md-4">
          <div class="text-muted small">Teléfono</div>
          <div class="fw-semibold">{{ $v($medio->telefono) }}</div>
        </div>

        <div class="col-md-12">
          <div class="text-muted small">Nombre</div>
          <div class="fw-semibold">{{ $v($medio->nombre) }}</div>
        </div>

        <div class="col-md-12">
          <div class="text-muted small">Nombre completo</div>
          <div class="fw-semibold">{{ $v($medio->nombre_completo) }}</div>
        </div>

        {{-- ================= CONTACTO ================= --}}
        <div class="col-12 mt-3">
          <h6 class="text-primary mb-2">Contacto</h6>
          <hr class="mt-0">
        </div>

        <div class="col-md-6">
          <div class="text-muted small">Contacto/Cargo</div>
          <div class="fw-semibold">{{ $v($medio->contacto_cargo) }}</div>
        </div>

        <div class="col-md-6">
          <div class="text-muted small">Celular del contacto</div>
          <div class="fw-semibold">{{ $v($medio->celular_contacto) }}</div>
        </div>

        <div class="col-md-6">
          <div class="text-muted small">E-mail</div>
          <div class="fw-semibold">
            @if(!empty($medio->email))
              <a href="mailto:{{ $medio->email }}" class="text-decoration-none">{{ $medio->email }}</a>
            @else
              —
            @endif
          </div>
        </div>

        {{-- ================= UBICACIÓN Y REDES ================= --}}
        <div class="col-12 mt-3">
          <h6 class="text-primary mb-2">Ubicación y Redes</h6>
          <hr class="mt-0">
        </div>

        <div class="col-md-12">
          <div class="text-muted small">Dirección</div>
          <div class="fw-semibold">{{ $v($medio->direccion) }}</div>
        </div>

        <div class="col-md-6">
          <div class="text-muted small">Web-site</div>
          <div class="fw-semibold">
            @if(!empty($medio->website))
              @php
                $url = $medio->website;
                if (!preg_match('~^https?://~i', $url)) $url = 'https://' . ltrim($url, '/');
              @endphp
              <a href="{{ $url }}" target="_blank" rel="noopener" class="text-decoration-none">
                {{ $medio->website }}
              </a>
            @else
              —
            @endif
          </div>
        </div>

        <div class="col-md-6">
          <div class="text-muted small">Red Social</div>
          <div class="fw-semibold">
            @if(!empty($medio->redsocial))
              @php
                $rs = $medio->redsocial;
                $isUrl = preg_match('~^https?://~i', $rs) || str_contains($rs, 'facebook.com') || str_contains($rs, 'instagram.com');
                if ($isUrl && !preg_match('~^https?://~i', $rs)) $rs = 'https://' . ltrim($rs, '/');
              @endphp

              @if($isUrl)
                <a href="{{ $rs }}" target="_blank" rel="noopener" class="text-decoration-none">{{ $medio->redsocial }}</a>
              @else
                {{ $medio->redsocial }}
              @endif
            @else
              —
            @endif
          </div>
        </div>

        {{-- ================= OBSERVACIONES ================= --}}
        <div class="col-12 mt-3">
          <h6 class="text-primary mb-2">Observaciones</h6>
          <hr class="mt-0">
        </div>

        <div class="col-12">
          <div class="fw-semibold">{{ $v($medio->observaciones) }}</div>
        </div>

        {{-- ================= NOTA ================= --}}
        <div class="col-12 mt-2">
          <div class="alert alert-light border mb-0">
            <div class="text-muted small">Nota</div>
            <div class="fw-semibold">Este registro se muestra en modo lectura.</div>
          </div>
        </div>

      </div>
    </div>
  </div>

</div>
@endsection
