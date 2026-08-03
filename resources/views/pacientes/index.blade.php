@extends('layouts.app')

@section('title', 'Pacientes')

@section('content')
<div class="container py-4">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h3 class="fw-bold mb-1">Pacientes Ingresados</h3>
      <div class="text-muted">Listado de pacientes ingresados</div>
    </div>

    <div class="d-flex gap-2">
      <a href="{{ route('pacientes.export.excel', request()->query()) }}" class="btn btn-outline-success">
        Exportar Excel
      </a>

      <a href="{{ route('pacientes.create') }}" class="btn btn-primary">
        Nuevo Paciente
      </a>
    </div>
  </div>

  @if(session('ok'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" id="autoCloseAlert">
      {{ session('ok') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
  @endif

  <div class="card border-0 shadow-sm">
    <div class="card-body">

      <form class="row g-2 mb-3" method="GET" action="{{ route('pacientes.index') }}">
        <div class="col-md-10">
          <input type="text" name="q" class="form-control"
                 placeholder="Buscar por nombre, DPI, estado, trámite, institución o lugar de ingreso..."
                 value="{{ $q ?? '' }}">
        </div>
        <div class="col-md-2 d-grid">
          <button class="btn btn-outline-secondary" type="submit">Buscar</button>
        </div>
      </form>

      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th>Nombre</th>
              <th>Prioridad</th>
              <th>Estado</th>
              <th>DPI</th>
              <th>Teléfono</th>
              <th>Consulta</th>
              <th>Rebaja o Trámite</th>
              <th>Lugar de Ingreso</th>
              <th class="text-end">Acciones</th>
            </tr>
          </thead>

          <tbody>
            @forelse($pacientes as $p)
              @php
                $prioridad = $p->prioridad ?? 'NORMAL';
                $estado = $p->estado_paciente ?? 'EN ESPERA';
              @endphp

              <tr class="row-click {{ $prioridad === 'PRIORITARIO' ? 'table-warning' : '' }}"
                  data-href="{{ route('pacientes.show', $p->id_paciente) }}">
                <td>{{ $p->nombre }}</td>

                <td>
                  @if($prioridad === 'PRIORITARIO')
                    <span class="badge bg-danger">PRIORITARIO</span>
                  @else
                    <span class="badge bg-secondary">NORMAL</span>
                  @endif
                </td>

                <td>
                  @if($estado === 'EN JORNADA')
                    <span class="badge bg-success">EN JORNADA</span>
                  @else
                    <span class="badge bg-warning text-dark">EN ESPERA</span>
                  @endif
                </td>

                <td>{{ $p->dpi }}</td>
                <td>{{ $p->telefono }}</td>
                <td>{{ $p->tipo_consulta }}</td>
                <td>
                  {{ $p->tipo_rebaja_tramite }}
                  @if($p->institucion_examen)
                    <div class="small text-muted">{{ $p->institucion_examen }}</div>
                  @endif
                </td>
                <td>{{ $p->lugar_ingreso }}</td>

                <td class="text-end">
                  <a href="{{ route('pacientes.edit', $p->id_paciente) }}"
                     class="btn btn-sm btn-outline-primary">✏️</a>

                  <form action="{{ route('pacientes.destroy', $p->id_paciente) }}"
                        method="POST" class="d-inline"
                        onsubmit="return confirm('¿Eliminar este paciente?');">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger" type="submit">🗑️</button>
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="10" class="text-center text-muted py-4">
                  No hay pacientes registrados.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 mt-3">
        <div class="text-muted small">
          Mostrando {{ $pacientes->firstItem() ?? 0 }}
          a {{ $pacientes->lastItem() ?? 0 }}
          de {{ $pacientes->total() }} registros
        </div>

        @if($pacientes->hasPages())
          <div>{{ $pacientes->onEachSide(1)->links() }}</div>
        @endif
      </div>

    </div>
  </div>
</div>

<style>
  tr.row-click { cursor: pointer; }
  tr.row-click:hover { background: rgba(13, 110, 253, .06); }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('tr.row-click').forEach(function (row) {
    row.addEventListener('click', function (event) {
      if (event.target.closest('a, button, form, input, textarea, select, label')) return;

      const url = row.getAttribute('data-href');
      if (url) window.location = url;
    });
  });

  const alerta = document.getElementById('autoCloseAlert');
  if (alerta) {
    setTimeout(function () {
      if (window.bootstrap) {
        bootstrap.Alert.getOrCreateInstance(alerta).close();
      } else {
        alerta.remove();
      }
    }, 3500);
  }
});
</script>
@endsection
