@extends('layouts.app')
@section('title','Proyectos')

@section('content')
<div class="container py-4">

  <div class="d-flex flex-wrap align-items-center justify-content-between mb-3 gap-3">

    <div>
      <h3 class="fw-bold mb-0">📁 Proyectos</h3>
      <div class="text-muted">Mantenimiento</div>
    </div>


        <div class="d-flex flex-wrap gap-2">
            <a class="btn btn-success d-inline-flex align-items-center gap-2"
            href="{{ route('proyectos.export.excel.descripcion', request()->query()) }}">
                <i class="bi bi-file-earmark-excel-fill"></i>
                Descripción
            </a>


        <a class="btn btn-success"
        href="{{ route('proyectos.export.excel', request()->query()) }}">
            <i class="bi bi-file-earmark-excel-fill me-1"></i>
            Asignaciones
        </a>


      <a href="{{ route('asignaciones.proyectos_usuarios.index') }}"
         class="btn btn-outline-primary">
        Asignar proyectos
      </a>

      <a href="{{ route('proyectos.create') }}"
         class="btn btn-primary">
        + Nuevo proyecto
      </a>
    </div>

  </div>

  @if(session('ok'))
    <div class="alert alert-success">{{ session('ok') }}</div>
  @endif

  <form class="row g-2 mb-3" method="GET" action="{{ route('proyectos.index') }}">
    <div class="col-sm-8 col-md-6 col-lg-4">
      <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Buscar por nombre...">
    </div>
    <div class="col-auto">
      <button class="btn btn-outline-secondary">Buscar</button>
      <a href="{{ route('proyectos.index') }}" class="btn btn-link">Limpiar</a>
    </div>
  </form>

  <div class="card shadow-sm">
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>Nombre</th>
            <th>Activo</th>
            <th>Encargados</th>
            <th class="text-end">Acciones</th>
          </tr>
        </thead>
        <tbody>
          @forelse($proyectos as $p)
            <tr>
              <td class="fw-semibold">{{ $p->nombre }}</td>

              <td>
                @if($p->activo)
                  <span class="badge bg-success">Sí</span>
                @else
                  <span class="badge bg-secondary">No</span>
                @endif
              </td>

              <td>
                @php
                  $encargados = '';
                  if (isset($p->usuarios) && $p->usuarios) {
                    $encargados = $p->usuarios->map(function($u){
                      return trim(($u->nombre ?? '').' '.($u->apellido ?? ''));
                    })->filter()->implode(', ');
                  }
                @endphp

                <span class="{{ $encargados ? '' : 'text-muted' }}">
                  {{ $encargados ?: '—' }}
                </span>
              </td>

              <td class="text-end">
                <a href="{{ route('proyectos.show',$p) }}" class="btn btn-outline-primary btn-sm">Ver</a>
                <a href="{{ route('proyectos.edit',$p) }}" class="btn btn-outline-primary btn-sm">✏️</a>

                <form action="{{ route('proyectos.destroy',$p) }}" method="POST" class="d-inline"
                      onsubmit="return confirm('¿Eliminar este proyecto?')">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-outline-danger btn-sm">🗑️</button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="4" class="text-center text-muted py-4">No hay proyectos.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="card-body border-top">
      {{ $proyectos->links() }}
    </div>
  </div>

</div>
@endsection
