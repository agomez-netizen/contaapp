@extends('layouts.app')
@section('title','Detalle Documento - Antigua')

@section('content')
<div class="container py-4">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h4 class="mb-0">🔎 Detalle Documento (Antigua)</h4>
      <div class="text-muted">Registro #{{ $row->id }}</div>
    </div>

    <div class="d-flex gap-2">
      <a href="{{ route('oficina.antigua.index') }}" class="btn btn-outline-secondary">← Volver</a>
      <a href="{{ route('oficina.antigua.edit', $row->id) }}" class="btn btn-primary">Editar</a>
    </div>
  </div>

  <div class="card border-0 shadow-sm">
    <div class="card-body">
      <div class="row g-3">

        <div class="col-md-3">
          <div class="text-muted">Tipo</div>
          <div class="fw-semibold">{{ $row->tipo_documento }}</div>
        </div>

        <div class="col-md-3">
          <div class="text-muted">Fecha</div>
          <div class="fw-semibold">{{ \Carbon\Carbon::parse($row->fecha_documento)->format('d/m/Y') }}</div>
        </div>

        <div class="col-md-3">
          <div class="text-muted">Pagada</div>
          @if($row->pagada)
            <span class="badge bg-success">Sí</span>
          @else
            <span class="badge bg-secondary">No</span>
          @endif
        </div>

        <div class="col-md-3">
          <div class="text-muted">Monto</div>
          <div class="fw-semibold">Q {{ number_format((float)$row->monto, 2) }}</div>
        </div>

        <div class="col-md-6">
          <div class="text-muted">Proyecto</div>
          <div class="fw-semibold">{{ $row->proyecto->nombre ?? '—' }}</div>
        </div>

        <div class="col-md-6">
          <div class="text-muted">Rubro</div>
          <div class="fw-semibold">{{ $row->rubro->nombre ?? '—' }}</div>
        </div>

        <div class="col-md-4">
          <div class="text-muted">No. Documento</div>
          <div class="fw-semibold">{{ $row->no_documento ?? '—' }}</div>
        </div>

        <div class="col-md-4">
          <div class="text-muted">Serie</div>
          <div class="fw-semibold">{{ $row->serie ?? '—' }}</div>
        </div>

        <div class="col-md-4">
          <div class="text-muted">Usuario</div>
          <div class="fw-semibold">{{ $row->usuario->nombre ?? '—' }}</div>
        </div>

        <div class="col-md-6">
          <div class="text-muted">Empresa</div>
          <div class="fw-semibold">{{ $row->empresa_nombre ?? '—' }}</div>
        </div>

        <div class="col-md-3">
          <div class="text-muted">NIT</div>
          <div class="fw-semibold">{{ $row->nit ?? '—' }}</div>
        </div>

        <div class="col-md-3">
          <div class="text-muted">Teléfono</div>
          <div class="fw-semibold">{{ $row->telefono ?? '—' }}</div>
        </div>

        <div class="col-md-12">
          <div class="text-muted">Descripción</div>
          <div class="fw-semibold" style="white-space:pre-wrap;">{{ $row->descripcion ?? '—' }}</div>
        </div>

        <div class="col-md-12">
          <div class="text-muted">Documento</div>
          @if($row->archivo_path)
            <a class="btn btn-outline-dark btn-sm" href="{{ asset($row->archivo_path) }}" target="_blank">
              Ver / Descargar
            </a>
            <span class="text-muted ms-2">{{ $row->archivo_original }}</span>
          @else
            <div class="text-muted">—</div>
          @endif
        </div>

      </div>
    </div>
  </div>

</div>
@endsection
