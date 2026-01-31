@extends('layouts.app')

@section('title', 'Detalle Donación')

@section('content')
<div class="container py-4">

  <div class="card shadow-sm border-0">
    {{-- ✅ Header AZUL --}}
    <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
      <h5 class="mb-0">Detalle Donación #{{ $donacion->id_donacion }}</h5>

      <div class="d-flex gap-2">
        <a href="{{ route('donaciones.index') }}" class="btn btn-sm btn-light">← Volver</a>
        <a href="{{ route('donaciones.edit', $donacion->id_donacion) }}" class="btn btn-sm btn-light">✏️ Editar</a>
      </div>
    </div>

    <div class="card-body">

      <div class="row g-3">
        {{-- DATOS PRINCIPALES --}}
        <div class="col-12">
          <h6 class="text-primary mb-2">Datos de la Donación</h6>
          <hr class="mt-0">
        </div>

        <div class="col-md-3"><div class="text-muted small">Fecha despachada</div><div class="fw-semibold">{{ $donacion->fecha_despachada ?? '—' }}</div></div>
        <div class="col-md-3"><div class="text-muted small">Empresa</div><div class="fw-semibold">{{ $donacion->empresa ?? '—' }}</div></div>
        <div class="col-md-3"><div class="text-muted small">NIT</div><div class="fw-semibold">{{ $donacion->nit ?? '—' }}</div></div>
        <div class="col-md-3"><div class="text-muted small">Contacto</div><div class="fw-semibold">{{ $donacion->contacto ?? '—' }}</div></div>

        <div class="col-md-3"><div class="text-muted small">Teléfono</div><div class="fw-semibold">{{ $donacion->telefono ?? '—' }}</div></div>
        <div class="col-md-3"><div class="text-muted small">Correo</div><div class="fw-semibold">{{ $donacion->correo ?? '—' }}</div></div>
        <div class="col-md-3"><div class="text-muted small">Unidades</div><div class="fw-semibold">{{ $donacion->unidades ?? 0 }}</div></div>
        <div class="col-md-3"><div class="text-muted small">Valor total donación</div><div class="fw-semibold">Q {{ number_format((float)($donacion->valor_total_donacion ?? 0), 2, '.', ',') }}</div></div>

        <div class="col-md-12">
          <div class="text-muted small">Descripción</div>
          <div class="fw-semibold">{{ $donacion->descripcion ?? '—' }}</div>
        </div>

        {{-- ENTREGA --}}
        <div class="col-12 mt-3">
          <h6 class="text-primary mb-2">Entrega y Recepción</h6>
          <hr class="mt-0">
        </div>

        <div class="col-md-4"><div class="text-muted small">Ubicación</div><div class="fw-semibold">{{ $donacion->ubicacion ?? '—' }}</div></div>
        <div class="col-md-3"><div class="text-muted small">Fecha que recibe</div><div class="fw-semibold">{{ $donacion->fecha_recibe ?? '—' }}</div></div>
        <div class="col-md-3"><div class="text-muted small">Quién recibe</div><div class="fw-semibold">{{ $donacion->quien_recibe ?? '—' }}</div></div>

        {{-- ✅ AQUÍ ES DONDE ANTES TE SALÍA "—" --}}
        <div class="col-md-3">
          <div class="text-muted small">Tipo de donación</div>
          <div class="fw-semibold">{{ $donacion->tipo_donacion ?? '—' }}</div>
        </div>

        <div class="col-md-2"><div class="text-muted small">Unidades entrega</div><div class="fw-semibold">{{ $donacion->unidades_entrega ?? 0 }}</div></div>
        <div class="col-md-4"><div class="text-muted small">Persona que gestionó</div><div class="fw-semibold">{{ $donacion->persona_gestiono ?? '—' }}</div></div>

        {{-- COSTOS --}}
        <div class="col-12 mt-3">
          <h6 class="text-primary mb-2">Costos y Mercado</h6>
          <hr class="mt-0">
        </div>

        <div class="col-md-3"><div class="text-muted small">Precio mercado unidad</div><div class="fw-semibold">Q {{ number_format((float)($donacion->precio_mercado_unidad ?? 0), 2, '.', ',') }}</div></div>
        <div class="col-md-3"><div class="text-muted small">Total mercado</div><div class="fw-semibold">Q {{ number_format((float)($donacion->total_mercado ?? 0), 2, '.', ',') }}</div></div>
        <div class="col-md-4"><div class="text-muted small">Referencia del mercado</div><div class="fw-semibold">{{ $donacion->referencia_mercado ?? '—' }}</div></div>
        <div class="col-md-3"><div class="text-muted small">Costo logística</div><div class="fw-semibold">Q {{ number_format((float)($donacion->costo_logistica ?? 0), 2, '.', ',') }}</div></div>

        <div class="col-md-12">
          <div class="text-muted small">Descripción logística</div>
          <div class="fw-semibold">{{ $donacion->descripcion_logistica ?? '—' }}</div>
        </div>

        {{-- PROYECTO / IMPACTO --}}
        <div class="col-12 mt-3">
          <h6 class="text-primary mb-2">Proyecto e Impacto</h6>
          <hr class="mt-0">
        </div>

        <div class="col-md-4"><div class="text-muted small">Proyecto</div><div class="fw-semibold">{{ $donacion->proyecto ?? '—' }}</div></div>
        <div class="col-md-3"><div class="text-muted small">Impacto (personas)</div><div class="fw-semibold">{{ number_format((int)($donacion->impacto_personas ?? 0)) }}</div></div>

        <div class="col-md-12">
          <div class="text-muted small">Comentarios</div>
          <div class="fw-semibold">{{ $donacion->comentarios ?? '—' }}</div>
        </div>

        {{-- DOCUMENTOS / REFERENCIAS --}}
        <div class="col-12 mt-3">
          <h6 class="text-primary mb-2">Documentos y Referencias</h6>
          <hr class="mt-0">
        </div>

        <div class="col-md-4"><div class="text-muted small">Recibo de empresa</div><div class="fw-semibold">
          @php $re = $donacion->recibo_empresa; @endphp
          {{ $re === null ? '—' : ((string)$re === '1' ? 'Sí (Existe)' : 'No (No existe)') }}
        </div></div>

        <div class="col-md-6"><div class="text-muted small">No. Referencia OSSHP</div><div class="fw-semibold">{{ $donacion->ref_osshp ?? '—' }}</div></div>
        <div class="col-md-6"><div class="text-muted small">Fecha referencia OSSHP</div><div class="fw-semibold">{{ $donacion->fecha_ref_osshp ?? '—' }}</div></div>
        <div class="col-md-6"><div class="text-muted small">No. Referencia SAT</div><div class="fw-semibold">{{ $donacion->ref_sat ?? '—' }}</div></div>
        <div class="col-md-6"><div class="text-muted small">Fecha referencia SAT</div><div class="fw-semibold">{{ $donacion->fecha_ref_sat ?? '—' }}</div></div>

      </div>

    </div>
  </div>

</div>
@endsection
