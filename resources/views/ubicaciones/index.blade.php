@extends('layouts.app')
@section('title','Ubicaciones')

@section('content')
<div class="container py-4">

  <div class="d-flex align-items-center justify-content-between mb-3">
    <div>
      <h3 class="fw-bold mb-0"><i class="bi bi-geo-alt-fill me-1"></i> Ubicaciones</h3>
      <div class="text-muted">Mantenimiento</div>
    </div>
    <a href="{{ route('ubicaciones.create') }}" class="btn btn-primary">
      <i class="bi bi-plus-lg me-1"></i> Nueva
    </a>
  </div>

  @if(session('ok'))
    <div class="alert alert-success">{{ session('ok') }}</div>
  @endif

  <div class="card shadow-sm">
    <div class="card-body">

      <form class="row g-2 mb-3" method="GET" action="{{ route('ubicaciones.index') }}">
        <div class="col-md-6">
          <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Buscar por nombre...">
        </div>

        <div class="col-md-3">
          <select name="estado" class="form-select">
            <option value=""  {{ $estado==='' ? 'selected' : '' }}>Todos</option>
            <option value="1" {{ $estado==='1' ? 'selected' : '' }}>Activos</option>
            <option value="0" {{ $estado==='0' ? 'selected' : '' }}>Inactivos</option>
          </select>
        </div>

        <div class="col-md-3 d-flex gap-2">
          <button class="btn btn-outline-secondary w-100">
            <i class="bi bi-search me-1"></i> Buscar
          </button>
          <a href="{{ route('ubicaciones.index') }}" class="btn btn-outline-dark" title="Limpiar">
            <i class="bi bi-arrow-counterclockwise"></i>
          </a>
        </div>
      </form>

      <div class="table-responsive">
        <table class="table align-middle">
          <thead class="table-light">
            <tr>
              <th>Nombre</th>
              <th>Estado</th>
              <th>Creado</th>
              <th class="text-end">Acciones</th>
            </tr>
          </thead>
          <tbody>
          @forelse($ubicaciones as $u)
            <tr>
              <td class="fw-semibold">{{ $u->nombre }}</td>
              <td>
                @if($u->activo)
                  <span class="badge text-bg-success">Activo</span>
                @else
                  <span class="badge text-bg-secondary">Inactivo</span>
                @endif
              </td>
              <td class="text-muted">{{ optional($u->created_at)->format('Y-m-d') }}</td>
              <td class="text-end">
                <div class="btn-group">
                  <a class="btn btn-sm btn-outline-primary" href="{{ route('ubicaciones.show',$u) }}" title="Ver">
                    <i class="bi bi-eye"></i>
                  </a>
                  <a class="btn btn-sm btn-outline-warning" href="{{ route('ubicaciones.edit',$u) }}" title="Editar">
                    <i class="bi bi-pencil"></i>
                  </a>

                  @if(Route::has('ubicaciones.toggle'))
                    <form method="POST" action="{{ route('ubicaciones.toggle',$u) }}">
                      @csrf @method('PATCH')
                      <button class="btn btn-sm btn-outline-info" title="Activar/Desactivar">
                        <i class="bi bi-toggle2-on"></i>
                      </button>
                    </form>
                  @endif

                  <form method="POST" action="{{ route('ubicaciones.destroy',$u) }}"
                        onsubmit="return confirm('¿Eliminar esta ubicación?');">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger" title="Eliminar">
                      <i class="bi bi-trash"></i>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="4" class="text-center text-muted py-4">No hay ubicaciones.</td>
            </tr>
          @endforelse
          </tbody>
        </table>
      </div>

      <div class="mt-3">
        {{ $ubicaciones->links() }}
      </div>

    </div>
  </div>
</div>
@endsection
