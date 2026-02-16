@extends('layouts.app')
@section('title','Documentos Antigua')

@section('content')
<div class="container py-4">

  {{-- MENSAJE --}}
  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
      {{ session('success') }}
      <button class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  {{-- HEADER --}}
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h4 class="mb-0">📁 Oficina - Antigua</h4>
      <div class="text-muted">Consolidado de Facturas y Cotizaciones</div>
    </div>

    <div class="d-flex gap-2">
      <a class="btn btn-outline-success"
         href="{{ route('oficina.antigua.export.excel', request()->query()) }}">
        Exportar Excel
      </a>

      <a href="{{ route('oficina.antigua.create') }}" class="btn btn-primary">
        + Nuevo
      </a>
    </div>
  </div>

  {{-- FILTROS --}}
  <form class="card border-0 shadow-sm mb-3" method="get">
    <div class="card-body">
      <div class="row g-2">

        <div class="col-md-4">
          <input class="form-control"
                 name="q"
                 value="{{ $filters['q'] ?? '' }}"
                 placeholder="Buscar empresa / documento / serie...">
        </div>

        <div class="col-md-2">
          <select class="form-select" name="tipo">
            <option value="">Tipo (Todos)</option>
            <option value="FACTURA" {{ ($filters['tipo'] ?? '')=='FACTURA'?'selected':'' }}>Factura</option>
            <option value="COTIZACION" {{ ($filters['tipo'] ?? '')=='COTIZACION'?'selected':'' }}>Cotización</option>
          </select>
        </div>

        <div class="col-md-2">
          <select class="form-select" name="pagada">
            <option value="">Pagada (Todas)</option>
            <option value="1" {{ ($filters['pagada'] ?? '')==='1'?'selected':'' }}>Sí</option>
            <option value="0" {{ ($filters['pagada'] ?? '')==='0'?'selected':'' }}>No</option>
          </select>
        </div>

        <div class="col-md-2">
          <input type="date" class="form-control" name="desde"
                 value="{{ $filters['desde'] ?? '' }}" title="Desde">
        </div>

        <div class="col-md-2">
          <input type="date" class="form-control" name="hasta"
                 value="{{ $filters['hasta'] ?? '' }}" title="Hasta">
        </div>

        <div class="col-md-4">
          <select class="form-select" name="proyecto">
            <option value="">Proyecto (Todos)</option>
            @foreach($proyectos as $p)
              <option value="{{ $p->id_proyecto }}"
                {{ (string)($filters['proyecto'] ?? '') === (string)$p->id_proyecto ? 'selected' : '' }}>
                {{ $p->nombre }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="col-md-4">
          <select class="form-select" name="rubro">
            <option value="">Rubro (Todos)</option>
            @foreach($rubros as $rb)
              <option value="{{ $rb->id_rubro }}"
                {{ (string)($filters['rubro'] ?? '') === (string)$rb->id_rubro ? 'selected' : '' }}>
                {{ $rb->nombre }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="col-md-2">
          <button class="btn btn-outline-secondary w-100">Aplicar</button>
        </div>

        <div class="col-md-2">
          <a href="{{ route('oficina.antigua.index') }}"
             class="btn btn-outline-dark w-100">
            Limpiar
          </a>
        </div>

      </div>
    </div>
  </form>



@php
  $hasTotal = !is_null($totalDocPago) && !is_null($noDocPago);
@endphp

<div class="alert {{ $hasTotal ? 'alert-info' : 'alert-light' }} d-flex justify-content-between align-items-center mt-2 mb-2">
  <div class="fw-bold">
    Total por No. Doc Pago
  </div>

  <div class="text-end">
    @if($hasTotal)
      <span class="me-2">Doc Pago: <b>{{ $noDocPago }}</b></span>
      <span>Total: <b>Q {{ number_format($totalDocPago, 2) }}</b></span>
    @else
      <span class="text-muted">Escribe el No. Doc Pago y presiona <b>Aplicar</b> para ver el total.</span>
    @endif
  </div>
</div>




  {{-- TABLA --}}
  <div class="card border-0 shadow-sm">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>Movimiento</th>
            <th>Fecha</th>
            <th>Usuario</th>
            <th>Proyecto</th>
            <th>Rubro</th>
            <th class="text-end">Monto</th>
            <th>Documento</th>
            <th>Pagada</th>

            {{-- NUEVO --}}
            <th>No. Doc Pago</th>
            <th>Fecha Pago</th>

            <th style="width:140px;">Acciones</th>
          </tr>
        </thead>

        <tbody>
        @forelse($rows as $r)
          <tr role="button"
              style="cursor:pointer"
              onclick="window.location='{{ route('oficina.antigua.show', $r->id) }}'">

            <td>
              <span class="badge bg-dark">{{ $r->tipo_documento }}</span>
            </td>

            <td>{{ \Carbon\Carbon::parse($r->fecha_documento)->format('d/m/Y') }}</td>

            <td>{{ $r->usuario->nombre ?? '—' }}</td>

            <td>{{ $r->proyecto->nombre ?? '—' }}</td>

            <td>{{ $r->rubro->nombre ?? '—' }}</td>

            <td class="text-end">
              Q {{ number_format((float)$r->monto, 2) }}
            </td>

            <td>
              @if($r->archivo_path)
                <a href="{{ asset($r->archivo_path) }}"
                   target="_blank"
                   onclick="event.stopPropagation()">
                  Ver
                </a>
              @else
                —
              @endif
            </td>

            <td>
              @if($r->pagada)
                <span class="badge bg-success">Sí</span>
              @else
                <span class="badge bg-secondary">No</span>
              @endif
            </td>

            {{-- NUEVO --}}
            <td>{{ $r->no_documento_pago ?? '—' }}</td>

            <td>
              @if(!empty($r->fecha_pago))
                {{ \Carbon\Carbon::parse($r->fecha_pago)->format('d/m/Y') }}
              @else
                —
              @endif
            </td>

            <td onclick="event.stopPropagation()">
              <a class="btn btn-sm btn-outline-primary"
                 href="{{ route('oficina.antigua.edit', $r->id) }}">
                ✏️
              </a>

              <form class="d-inline"
                    method="post"
                    action="{{ route('oficina.antigua.destroy', $r->id) }}"
                    onsubmit="event.stopPropagation(); return confirm('¿Eliminar este registro?');">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-outline-danger">
                  🗑️
                </button>
              </form>
            </td>

          </tr>
        @empty
          <tr>
            <td colspan="11" class="text-center text-muted py-4">
              No hay registros.
            </td>
          </tr>
        @endforelse
        </tbody>

      </table>
    </div>

    <div class="card-body">
      {{ $rows->links() }}
    </div>
  </div>

</div>
@endsection
