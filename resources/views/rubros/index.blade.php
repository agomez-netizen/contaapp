@extends('layouts.app')

@section('title', 'Rubros')

@section('content')
<div class="container py-4">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h3 class="fw-bold mb-0">Rubros</h3>
      <div class="text-muted">Mantenimiento de rubros</div>
    </div>
    <a href="{{ route('rubros.create') }}" class="btn btn-primary">
      + Nuevo Rubro
    </a>
  </div>

  @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
  @endif

  <div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
      <form class="row g-2" method="GET" action="{{ route('rubros.index') }}">
        <div class="col-md-6">
          <input type="text" name="q" value="{{ $q ?? '' }}" class="form-control" placeholder="Buscar por nombre...">
        </div>

        <div class="col-md-3">
          <select name="activo" class="form-select">
            <option value="" {{ ($activo ?? '') === '' ? 'selected' : '' }}>Todos</option>
            <option value="1" {{ (string)($activo ?? '') === '1' ? 'selected' : '' }}>Activos</option>
            <option value="0" {{ (string)($activo ?? '') === '0' ? 'selected' : '' }}>Inactivos</option>
          </select>
        </div>

        <div class="col-md-3 d-flex gap-2">
          <button class="btn btn-outline-primary w-100" type="submit">Filtrar</button>
          <a class="btn btn-outline-secondary w-100" href="{{ route('rubros.index') }}">Limpiar</a>
        </div>
      </form>
    </div>
  </div>

  <div class="card border-0 shadow-sm">
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th style="width: 90px;">ID</th>
            <th>Nombre</th>
            <th style="width: 120px;">Estado</th>
            <th style="width: 220px;" class="text-end">Acciones</th>
          </tr>
        </thead>
        <tbody>
          @forelse($rubros as $r)
            <tr>
              <td>{{ $r->id_rubro }}</td>
              <td class="fw-semibold">{{ $r->nombre }}</td>
              <td>
                @if($r->activo)
                  <span class="badge bg-success">Activo</span>
                @else
                  <span class="badge bg-secondary">Inactivo</span>
                @endif
              </td>
              <td class="text-end">
                <form action="{{ route('rubros.toggle', $r->id_rubro) }}" method="POST" class="d-inline">
                  @csrf
                  @method('PATCH')
                  <button class="btn btn-sm btn-outline-warning" type="submit">
                    {{ $r->activo ? 'Desactivar' : 'Activar' }}
                  </button>
                </form>

                <a href="{{ route('rubros.edit', $r->id_rubro) }}" class="btn btn-sm btn-outline-primary">
                  ✏️
                </a>

                <form action="{{ route('rubros.destroy', $r->id_rubro) }}" method="POST" class="d-inline"
                      onsubmit="return confirm('¿Eliminar este rubro?');">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-sm btn-outline-danger" type="submit">🗑️</button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="4" class="text-center text-muted py-4">
                No hay rubros registrados.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if($rubros->hasPages())
      <div class="card-body">
        {{ $rubros->links() }}
      </div>
    @endif
  </div>

</div>
@endsection
