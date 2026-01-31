@extends('layouts.app')

@section('title', 'Pacientes')

@section('content')
<div class="container py-4">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h3 class="fw-bold mb-1">📋 Pacientes Ingresados</h3>
      <div class="text-muted">Listado De Pacientes Ingresados</div>
    </div>

    {{-- ✅ Botones derecha --}}
    <div class="d-flex gap-2">
      <a href="{{ route('pacientes.export.excel', request()->query()) }}"
         class="btn btn-outline-success">
        📗 Exportar Excel
      </a>

      <a href="{{ route('pacientes.create') }}" class="btn btn-primary">
        ✚ Nuevo Paciente
      </a>
    </div>
  </div>

  {{-- Alert éxito (auto-cierra) --}}
  @if(session('ok'))
    <div class="alert alert-success alert-dismissible fade show"
         role="alert"
         id="autoCloseAlert">
      {{ session('ok') }}

      <button type="button"
              class="btn-close"
              data-bs-dismiss="alert"
              aria-label="Cerrar"></button>
    </div>

    <script>
      setTimeout(() => {
        const alertEl = document.getElementById('autoCloseAlert');
        if (alertEl && window.bootstrap) {
          const bsAlert = bootstrap.Alert.getOrCreateInstance(alertEl);
          bsAlert.close();
        } else if (alertEl) {
          alertEl.remove();
        }
      }, 3500);
    </script>
  @endif

  <div class="card border-0 shadow-sm">
    <div class="card-body">

      {{-- Buscador --}}
      <form class="row g-2 mb-3" method="GET" action="{{ route('pacientes.index') }}">
        <div class="col-md-10">
          <input type="text" name="q" class="form-control"
                 placeholder="Buscar por nombre, DPI, teléfono, departamento o municipio..."
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
              <th>#</th>
              <th>Nombre</th>
              <th>DPI</th>
              <th>Teléfono</th>
              <th>Departamento</th>
              <th>Municipio</th>
              <th>Consulta</th>
              <th class="text-end">Acciones</th>
            </tr>
          </thead>

          <tbody>
            @forelse($pacientes as $p)
              <tr class="row-click"
                  data-href="{{ route('pacientes.show', $p->id_paciente) }}">

                <td class="fw-semibold">{{ $p->id_paciente }}</td>
                <td>{{ $p->nombre }}</td>
                <td>{{ $p->dpi }}</td>
                <td>{{ $p->telefono }}</td>
                <td>{{ $p->departamento }}</td>
                <td>{{ $p->municipio }}</td>
                <td>{{ $p->tipo_consulta }}</td>

                <td class="text-end">
                  <a href="{{ route('pacientes.edit', $p->id_paciente) }}"
                     class="btn btn-sm btn-outline-primary"
                     title="Editar">
                    ✏️
                  </a>

                  <form action="{{ route('pacientes.destroy', $p->id_paciente) }}"
                        method="POST"
                        class="d-inline"
                        onsubmit="return confirm('¿Eliminar este paciente?');">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger" type="submit" title="Eliminar">
                      🗑️
                    </button>
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="8" class="text-center text-muted py-4">
                  No hay pacientes registrados aún.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      {{-- Paginación + conteo --}}
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 mt-3">
        <div class="text-muted small">
          Mostrando
          <strong>{{ $pacientes->firstItem() ?? 0 }}</strong>
          a
          <strong>{{ $pacientes->lastItem() ?? 0 }}</strong>
          de
          <strong>{{ $pacientes->total() }}</strong>
          registros
        </div>

        @if($pacientes->hasPages())
          <div>
            {{ $pacientes->onEachSide(1)->links() }}
          </div>
        @endif
      </div>

    </div>
  </div>

</div>

{{-- Fila clickeable sin romper botones --}}
<style>
  tr.row-click { cursor: pointer; }
  tr.row-click:hover { background: rgba(13,110,253,.06); }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('tr.row-click').forEach(function (row) {
    row.addEventListener('click', function (e) {
      if (e.target.closest('a, button, form, input, textarea, select, label')) return;
      const url = row.getAttribute('data-href');
      if (url) window.location = url;
    });
  });
});
</script>
@endsection
