@extends('layouts.app')

@section('title', 'Medios')

@section('content')
<div class="container py-4">

  <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <div>
      <h3 class="fw-bold mb-0">📢 Medios</h3>
      <div class="text-muted">Listado de medios de comunicación</div>
    </div>

    <div class="d-flex gap-2">
      {{-- ✅ Exportar Excel --}}
      <a href="{{ route('medios.export.excel', request()->query()) }}" class="btn btn-outline-success">
        📗 Exportar Excel
      </a>

      <a href="{{ route('medios.create') }}" class="btn btn-primary">
        + Nuevo Medio
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

  {{-- Buscador --}}
  <div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
      <form method="GET" action="{{ route('medios.index') }}" class="row g-2 align-items-end">
        <div class="col-md-6">
          <label class="form-label">Buscar</label>
          <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Medio, nombre, email...">
        </div>

        <div class="col-md-3">
          <label class="form-label">Tipo</label>
          <select name="tipo" class="form-select">
            <option value="">Todos</option>
            @foreach (['Local','Nacional','Internacional'] as $t)
              <option value="{{ $t }}" {{ request('tipo')===$t ? 'selected' : '' }}>{{ $t }}</option>
            @endforeach
          </select>
        </div>

        <div class="col-md-3 d-flex gap-2">
          <button class="btn btn-outline-primary w-100" type="submit">Filtrar</button>
          <a class="btn btn-outline-secondary w-100" href="{{ route('medios.index') }}">Limpiar</a>
        </div>
      </form>
    </div>
  </div>

  <div class="card border-0 shadow-sm">
    <div class="card-body p-0">

      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>#</th>
              <th>Medio</th>
              <th>Tipo</th>
              <th>Nombre</th>
              <th>Contacto/Cargo</th>
              <th>Teléfono</th>
              <th>Email</th>
              <th class="text-end">Acciones</th>
            </tr>
          </thead>

          <tbody>
            @forelse($medios as $m)
              {{-- ✅ Fila clickeable a SHOW --}}
              <tr class="row-click" data-href="{{ route('medios.show', $m->id_medio) }}">
                <td class="text-muted">{{ $m->id_medio }}</td>
                <td class="fw-semibold">{{ $m->medio }}</td>
                <td><span class="badge bg-secondary">{{ $m->tipo }}</span></td>

                <td>
                  <div>{{ $m->nombre }}</div>
                  @if($m->nombre_completo)
                    <div class="text-muted small">{{ $m->nombre_completo }}</div>
                  @endif
                </td>

                <td>{{ $m->contacto_cargo }}</td>
                <td>{{ $m->telefono }}</td>
                <td>{{ $m->email }}</td>

                <td class="text-end">
                  <a href="{{ route('medios.edit', $m->id_medio) }}" class="btn btn-sm btn-outline-primary" title="Editar">
                    ✏️
                  </a>

                  <form action="{{ route('medios.destroy', $m->id_medio) }}"
                        method="POST"
                        class="d-inline"
                        onsubmit="return confirm('¿Eliminar este medio?');">
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
                <td colspan="8" class="text-center py-4 text-muted">
                  No hay medios registrados.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

    </div>

    @if(method_exists($medios, 'links'))
      <div class="card-footer bg-white border-0">
        {{ $medios->links() }}
      </div>
    @endif
  </div>

</div>

{{-- ✅ Hover + click sin romper botones --}}
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
