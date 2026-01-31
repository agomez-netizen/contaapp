@extends('layouts.app')

@section('content')

<style>
/* ===== TOGGLE PERSONALIZADO ===== */
.switch {
  position: relative;
  display: inline-block;
  width: 44px;
  height: 24px;
}
.switch input { display: none; }
.slider {
  position: absolute;
  inset: 0;
  cursor: pointer;
  background-color: #d1d5db;
  border-radius: 999px;
  transition: .2s;
}
.slider:before {
  content: "";
  position: absolute;
  height: 18px;
  width: 18px;
  left: 3px;
  top: 3px;
  background-color: #fff;
  border-radius: 50%;
  transition: .2s;
  box-shadow: 0 1px 2px rgba(0,0,0,.2);
}
.switch input:checked + .slider {
  background-color: #0d6efd;
}
.switch input:checked + .slider:before {
  transform: translateX(20px);
}
tr.js-row:hover {
  background-color: #f8f9fa;
}
</style>

<div class="container py-3">

  {{-- HEADER --}}
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h4 class="mb-0">Donaciones Registradas</h4>
      <small class="text-muted">Control Interno AAPOS</small>
    </div>

    <div class="d-flex gap-2">
      <a href="{{ route('donaciones.export.excel', request()->query()) }}"
         class="btn btn-outline-success">📗 Exportar Excel</a>

      <a href="{{ route('donaciones.export.pdf', request()->query()) }}"
         class="btn btn-outline-danger">📄 Exportar PDF</a>

      <a href="{{ route('donaciones.create') }}"
         class="btn btn-primary">✚ Nueva Donación</a>
    </div>
  </div>

  {{-- TABLA --}}
  <div class="card shadow-sm">
    <div class="card-body table-responsive">

      <table class="table table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Fecha</th>
            <th>Empresa</th>
            <th>NIT</th>
            <th>Tipo</th>
            <th>Ubicación</th>
            <th>Proyecto</th>
            <th class="text-end">Valor</th>
            <th class="text-end">Impacto</th>
            <th>Registró</th>
            <th class="text-center">Bloqueo</th>
            <th class="text-center">Acciones</th>
          </tr>
        </thead>

        <tbody>
        @forelse($donaciones as $d)
          <tr class="js-row"
              data-href="{{ route('donaciones.show', $d->id_donacion) }}"
              style="cursor:pointer">

            <td>{{ $loop->iteration }}</td>
            <td>{{ \Carbon\Carbon::parse($d->fecha_despachada)->format('d/m/Y') }}</td>
            <td>{{ $d->empresa }}</td>
            <td>{{ $d->nit }}</td>

            <td><span class="badge bg-primary">{{ $d->tipo_donacion }}</span></td>
            <td><span class="badge bg-success">{{ $d->ubicacion }}</span></td>
            <td>{{ $d->proyecto }}</td>

            <td class="text-end">
              Q {{ number_format($d->valor_total_donacion, 2) }}
            </td>

            <td class="text-end">
              {{ number_format($d->impacto_personas) }}
            </td>

            <td>{{ $d->usuario }}</td>

            {{-- TOGGLE --}}
            <td class="text-center" onclick="event.stopPropagation();">
              <div class="d-flex justify-content-center align-items-center gap-2">
                <label class="switch">
                  <input type="checkbox"
                         class="js-toggle-bloqueo"
                         data-id="{{ $d->id_donacion }}"
                         @checked((int)$d->bloqueado === 1)>
                  <span class="slider"></span>
                </label>
                <small class="text-muted">
                  {{ (int)$d->bloqueado === 1 ? 'Bloqueado' : 'Activo' }}
                </small>
              </div>
            </td>

            {{-- ACCIONES --}}
            <td class="text-center" onclick="event.stopPropagation();">
              <div class="d-flex justify-content-center gap-1">

                <a href="{{ route('donaciones.pdf', $d->id_donacion) }}"
                   class="btn btn-outline-secondary btn-sm"
                   target="_blank">📄</a>

                @if((int)$d->bloqueado === 0)
                  <a href="{{ route('donaciones.edit', $d->id_donacion) }}"
                     class="btn btn-outline-primary btn-sm">✏️</a>

                  <form method="POST"
                        action="{{ route('donaciones.destroy', $d->id_donacion) }}"
                        onsubmit="return confirm('¿Eliminar esta donación?');">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-outline-danger btn-sm">🗑️</button>
                  </form>
                @else
                  <span class="badge bg-secondary">🔒</span>
                @endif

              </div>
            </td>

          </tr>
        @empty
          <tr>
            <td colspan="12" class="text-center text-muted py-4">
              No hay registros
            </td>
          </tr>
        @endforelse
        </tbody>
      </table>

      {{ $donaciones->links() }}

    </div>
  </div>
</div>

{{-- JS: click en fila --}}
<script>
document.querySelectorAll('tr.js-row').forEach(row => {
  row.addEventListener('click', () => {
    window.location.href = row.dataset.href;
  });
});
</script>

{{-- JS: toggle bloqueo --}}
<script>
document.querySelectorAll('.js-toggle-bloqueo').forEach(el => {
  el.addEventListener('change', async function () {
    const checkbox = this;
    const id = checkbox.dataset.id;
    const prev = !checkbox.checked;

    try {
      const res = await fetch(`/donaciones/${id}/toggle-bloqueo`, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Accept': 'application/json'
        }
      });

      if (!res.ok) throw new Error();
      location.reload();
    } catch (e) {
      checkbox.checked = prev;
      alert('Error al cambiar el bloqueo');
    }
  });
});
</script>

@endsection
