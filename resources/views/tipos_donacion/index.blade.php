@extends('layouts.app')
@section('title','Tipos de Donación')

@section('content')
<div class="container py-4">

  <div class="d-flex flex-wrap gap-2 align-items-center justify-content-between mb-3">
    <div>
      <h3 class="fw-bold mb-0">🎁 Tipos de Donación</h3>
      <div class="text-muted">Mantenimiento</div>
    </div>

    <a href="{{ route('tipos_donacion.create') }}" class="btn btn-primary">
      + Nuevo tipo
    </a>
  </div>

  @if(session('ok'))
    <div class="alert alert-success">{{ session('ok') }}</div>
  @endif

  <form class="row g-2 mb-3" method="GET" action="{{ route('tipos_donacion.index') }}">
    <div class="col-sm-8 col-md-6 col-lg-4">
      <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Buscar por nombre...">
    </div>
    <div class="col-auto">
      <button class="btn btn-outline-secondary">Buscar</button>
      <a href="{{ route('tipos_donacion.index') }}" class="btn btn-link">Limpiar</a>
    </div>
  </form>

  <div class="card shadow-sm">
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Activo</th>
            <th class="text-end">Acciones</th>
          </tr>
        </thead>
        <tbody>
          @forelse($tipos as $t)
            <tr>
              <td>{{ $t->id_tipo_donacion }}</td>
              <td class="fw-semibold">{{ $t->nombre }}</td>
              <td>
                @if($t->activo)
                  <span class="badge bg-success">Sí</span>
                @else
                  <span class="badge bg-secondary">No</span>
                @endif
              </td>
              <td class="text-end">
                <a class="btn btn-sm btn-outline-primary" href="{{ route('tipos_donacion.show',$t) }}">Ver</a>
                <a class="btn btn-sm btn-outline-warning" href="{{ route('tipos_donacion.edit',$t) }}">Editar</a>

                <form class="d-inline"
                      action="{{ route('tipos_donacion.destroy',$t) }}"
                      method="POST"
                      onsubmit="return confirm('¿Eliminar este tipo de donación?')">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="4" class="text-center text-muted py-4">
                No hay tipos de donación.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <div class="mt-3">
    {{ $tipos->links() }}
  </div>

</div>
@endsection
