@extends('layouts.app')

@section('content')
<div class="container">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Avances por fecha</h3>
    <a href="{{ route('avances.create') }}" class="btn btn-outline-secondary">← Registrar avance</a>
  </div>

  {{-- Filtros --}}
  <form method="GET" action="{{ route('avances.byDate') }}" class="card p-3 mb-3">
    <div class="row g-3 align-items-end">
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
        <button class="btn btn-primary">Filtrar</button>
      </div>
    </div>
  </form>

  {{-- Resultados --}}
  @forelse($grouped as $fecha => $items)
    <div class="mb-4">
      <div class="d-flex align-items-center gap-2 mb-2">
        <span class="badge bg-dark">
            {{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }}
            </span>
        <small class="text-muted">({{ $items->count() }} avances)</small>
      </div>

      @foreach($items as $a)
        <div class="card mb-2">
          <div class="card-body">
            <div class="d-flex justify-content-between">
              <div>
                <div class="fw-bold">📁 {{ $a->proyecto->nombre ?? 'Sin proyecto' }}</div>
                <div>{{ $a->descripcion }}</div>
                <small class="text-muted">
                  ✍️
                    @if($a->usuario)
                    {{ $a->usuario->nombre }} {{ $a->usuario->apellido }}
                    @else
                    Usuario eliminado
                    @endif
                </small>
              </div>

              <small class="text-muted">
                🕒 {{ optional($a->created_at)->format('d/m/Y H:i') }}
              </small>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  @empty
    <div class="alert alert-info">No hay avances con esos filtros.</div>
  @endforelse

</div>
@endsection
