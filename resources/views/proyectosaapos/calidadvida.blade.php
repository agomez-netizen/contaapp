@extends('layouts.app')
@section('title','Calidad de Vida')

@php
  function q($n){
    return 'Q' . number_format((float)$n, 2, '.', ',');
  }

  $avance = ($donacionInicial > 0)
    ? (($totEjecutado + $totProceso) / $donacionInicial) * 100
    : 0;
@endphp

@section('content')
<div class="container py-4">

  <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
    <div>
      <h3 class="fw-bold mb-1">Proyecto: Calidad de Vida</h3>
      <div class="text-muted">Tabla De Ejecución Por Rubro</div>
    </div>

    <div class="text-end">
      <div class="small text-muted">Monto Actual</div>
      <div class="fs-4 fw-bold">{{ q($donacionInicial) }}</div>
      <div class="small">
        Ejecutado + En Proceso:
        <span class="fw-semibold">{{ number_format($avance, 0) }}%</span>
      </div>
    </div>
  </div>

  @if(session('ok'))
    <div class="alert alert-success py-2">{{ session('ok') }}</div>
  @endif

  {{-- FORMULARIO --}}
  <div class="card shadow-sm mb-3">
    <div class="card-body">
      <h6 class="fw-bold mb-3">Agregar rubro</h6>

      <form method="POST" action="{{ route('calidadvida.store') }}" class="row g-2">
        @csrf

        <div class="col-12 col-md-4">
          <label class="form-label mb-1">Rubro</label>
          <input name="rubro" class="form-control" required value="{{ old('rubro') }}" placeholder="Ej: RRHH">
        </div>

        <div class="col-6 col-md-2">
          <label class="form-label mb-1">Monto (Q)</label>
          <input name="monto" type="number" step="0.01" min="0" class="form-control" required value="{{ old('monto',0) }}">
        </div>

        <div class="col-6 col-md-2">
          <label class="form-label mb-1">Ejecutado</label>
          <input name="ejecutado" type="number" step="0.01" min="0" class="form-control" value="{{ old('ejecutado',0) }}">
        </div>

        <div class="col-6 col-md-2">
          <label class="form-label mb-1">En Proceso</label>
          <input name="en_proceso" type="number" step="0.01" min="0" class="form-control" value="{{ old('en_proceso',0) }}">
        </div>

        <div class="col-6 col-md-2">
          <label class="form-label mb-1">Pendiente</label>
          <input name="pendiente" type="number" step="0.01" min="0" class="form-control" value="{{ old('pendiente',0) }}">
        </div>

        <div class="col-6 col-md-2">
          <label class="form-label mb-1">No. Documento</label>
          <input name="no_documento" class="form-control"
                 value="{{ old('no_documento') }}"
                 placeholder="Ej: FAC-00123">
        </div>

        <div class="col-12 col-md-6">
          <label class="form-label mb-1">Descripción</label>
          <input name="descripcion" class="form-control"
                 value="{{ old('descripcion') }}"
                 placeholder="Detalle del gasto">
        </div>

        <div class="col-12 d-flex justify-content-end gap-2 mt-2">
          <button class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Agregar
          </button>
        </div>
      </form>

      @if($errors->any())
        <div class="alert alert-danger mt-3 mb-0">
          <ul class="mb-0">
            @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
          </ul>
        </div>
      @endif
    </div>
  </div>

  {{-- TABLA --}}
  <div class="card shadow-sm">
    <div class="card-body p-0">
      <div class="">
        <table class="table table-bordered align-middle mb-0">
          <thead class="table-light">
            <tr class="text-center">
                <th style="width:16%">Rubro</th>
                <th style="width:10%">Monto (Q)</th>
                <th style="width:10%">Ejecutado</th>
                <th style="width:10%">En Proceso</th>
                <th style="width:10%">Pendiente</th>
                <th style="width:12%">No. Documento</th>
                <th style="width:22%">Descripción</th>
                <th style="width:10%">Acciones</th>
            </tr>
          </thead>

          <tbody>
            @forelse($items as $it)
              <tr>
                <td>
                  <form method="POST" action="{{ route('calidadvida.update', $it) }}" class="d-flex gap-2">
                    @csrf @method('PUT')
                    <input name="rubro" class="form-control form-control-sm" value="{{ $it->rubro }}" required>
                </td>

                <td>
                    <input name="monto" type="number" step="0.01" min="0"
                      class="form-control form-control-sm text-end"
                      value="{{ $it->monto }}" required>
                </td>

                <td>
                    <input name="ejecutado" type="number" step="0.01" min="0"
                      class="form-control form-control-sm text-end"
                      value="{{ $it->ejecutado }}">
                </td>

                <td>
                    <input name="en_proceso" type="number" step="0.01" min="0"
                      class="form-control form-control-sm text-end"
                      value="{{ $it->en_proceso }}">
                </td>

                <td>
                    <input name="pendiente" type="number" step="0.01" min="0"
                      class="form-control form-control-sm text-end"
                      value="{{ $it->pendiente }}">
                </td>

                <td>
                    <input name="no_documento"
                      class="form-control form-control-sm"
                      value="{{ $it->no_documento }}">
                </td>

                <td>
                    <input name="descripcion"
                      class="form-control form-control-sm"
                      value="{{ $it->descripcion }}">
                </td>

                <td class="text-center">
                    <button class="btn btn-sm btn-outline-success me-2" title="Guardar">
                      <i class="bi bi-check2-circle"></i>
                    </button>
                  </form>

                  <form method="POST" action="{{ route('calidadvida.destroy', $it) }}"
                        class="d-inline"
                        onsubmit="return confirm('¿Eliminar este rubro?');">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger" title="Eliminar">
                      <i class="bi bi-trash"></i>
                    </button>
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="8" class="text-center text-muted py-4">
                  No hay rubros aún. Agrega el primero arriba 👆
                </td>
              </tr>
            @endforelse
          </tbody>

          <tfoot>
            <tr class="fw-bold">
              <td class="text-end">TOTAL PROYECTO</td>
              <td class="text-end">{{ q($totMonto) }}</td>
              <td class="text-end">{{ q($totEjecutado) }}</td>
              <td class="text-end">{{ q($totProceso) }}</td>
              <td class="text-end">{{ q($totPendiente) }}</td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
          </tfoot>

        </table>
      </div>
    </div>
  </div>

</div>
@endsection
