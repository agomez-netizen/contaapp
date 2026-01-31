{{-- resources/views/avances/by_date.blade.php --}}
@extends('layouts.app')

@section('title', 'Avances por fecha')

@section('content')
<div class="container py-4">

  {{-- Header --}}
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Avances por fecha</h4>

    <a class="btn btn-outline-secondary" href="{{ route('avances.create') }}">
      ← Registrar avance
    </a>
  </div>

  {{-- Filtros --}}
  <div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
      <form method="GET" action="{{ route('avances.byDate') }}" class="row g-3 align-items-end">

        <div class="col-md-5">
          <label class="form-label">Proyecto</label>
          <select name="id_proyecto" class="form-select">
            <option value="">— Todos —</option>
            @foreach($proyectos as $p)
              <option value="{{ $p->id_proyecto }}" @selected((string)$idProyecto === (string)$p->id_proyecto)>
                {{ $p->nombre }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="col-md-3">
          <label class="form-label">Desde</label>
          <input type="date" name="desde" value="{{ $desde }}" class="form-control">
        </div>

        <div class="col-md-3">
          <label class="form-label">Hasta</label>
          <input type="date" name="hasta" value="{{ $hasta }}" class="form-control">
        </div>

        <div class="col-md-1 d-grid">
          <button class="btn btn-primary" type="submit">Filtrar</button>
        </div>

      </form>
    </div>
  </div>

  {{-- ✅ BARRA ROJA: acciones debajo de filtros --}}

<div style="background:#ffeb3b; padding:10px; border-radius:10px; margin:10px 0; font-weight:800;">
  ✅ ESTOY EN por_fecha.blade.php
  <a href="{{ route('avances.dashboard') }}" style="margin-left:12px; text-decoration:none;">
    📊 Ver Dashboard
  </a>
</div>

    {{-- opcional: limpiar filtros --}}
    <a class="btn btn-outline-secondary" href="{{ route('avances.byDate') }}">
      <i class="bi bi-arrow-counterclockwise me-1"></i> Limpiar filtros
    </a>
  </div>

  @if($avances->count() === 0)
    <div class="alert alert-info mb-0">No hay avances con esos filtros.</div>
  @else

    {{-- Timeline --}}
    <div class="card shadow-sm border-0">
      <div class="card-body">

        @foreach($agrupados as $fecha => $items)
          <div class="mb-4">

            {{-- Encabezado de fecha --}}
            <div class="d-flex align-items-center gap-2 mb-2">
              <span class="badge bg-dark">
                {{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }}
              </span>
              <span class="text-muted">
                ({{ $items->count() }} avance{{ $items->count() > 1 ? 's' : '' }})
              </span>
            </div>

            {{-- Línea vertical --}}
            <div class="border-start ps-3 ms-2">

              @foreach($items as $a)
                <div class="mb-3 p-3 rounded-3 shadow-sm bg-white">

                  <div class="d-flex justify-content-between flex-wrap gap-2">
                    <div class="fw-semibold">
                      <i class="bi bi-folder2-open me-1"></i>
                      {{ $a->proyecto->nombre ?? 'Proyecto' }}
                    </div>

                    <div class="text-muted small">
                      <i class="bi bi-clock me-1"></i>
                      {{ optional($a->created_at)->format('d/m/Y H:i') }}
                    </div>
                  </div>

                  {{-- Contenido con formato --}}
                  <div class="mt-2 avance-html">
                    {!! $a->descripcion !!}
                  </div>

                </div>
              @endforeach

            </div>
          </div>
        @endforeach

        <div class="mt-3">
          {{ $avances->links() }}
        </div>

      </div>
    </div>
  @endif

</div>
@endsection

@push('scripts')
<style>
  /* Que el HTML del editor se vea bonito y no rompa el layout */
  .avance-html img{
    max-width: 100%;
    height: auto;
    border-radius: 12px;
    margin-top: .5rem;
  }
  .avance-html table{
    width: 100%;
    border-collapse: collapse;
    margin-top: .5rem;
  }
  .avance-html table td, .avance-html table th{
    border: 1px solid #e5e7eb;
    padding: .5rem;
  }
  .avance-html a{
    word-break: break-word;
  }
</style>
@endpush
