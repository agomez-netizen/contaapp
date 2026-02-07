@extends('layouts.app')

@section('title', 'Asignar Proyectos a Usuarios')

@section('content')
<div class="container py-4">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h4 class="mb-0">Asignar Proyectos a Usuarios</h4>
      <div class="text-muted">Selecciona un usuario y asigna sus proyectos</div>
    </div>
        <a class="btn btn-outline-secondary" href="{{ route('proyectos.index') }}">
      ← Volver
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
      <form class="row g-2" method="GET" action="{{ route('asignaciones.proyectos_usuarios.index') }}">
        <div class="col-md-6">
          <input type="text" class="form-control" name="q" value="{{ $q }}" placeholder="Buscar por nombre, apellido o usuario...">
        </div>
        <div class="col-md-3">
          <button class="btn btn-primary w-100" type="submit">Buscar</button>
        </div>
        <div class="col-md-3">
          <a class="btn btn-outline-secondary w-100" href="{{ route('asignaciones.proyectos_usuarios.index') }}">Limpiar</a>
        </div>
      </form>
    </div>
  </div>

  <div class="card border-0 shadow-sm">
    <div class="table-responsive">
      <table class="table table-hover mb-0 align-middle">
        <thead class="table-light">
          <tr>
            <th>Usuario</th>
            <th class="text-end">Acción</th>
          </tr>
        </thead>
        <tbody>
          @forelse($usuarios as $u)
            <tr>
              <td>
                <div class="fw-semibold">{{ $u->nombre }} {{ $u->apellido }}</div>
              </td>
              <td class="text-end">
                <a class="btn btn-sm btn-outline-primary"
                   href="{{ route('asignaciones.proyectos_usuarios.edit', $u->id_usuario) }}">
                   Asignar proyectos
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="4" class="text-center text-muted py-4">No hay usuarios</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="card-footer bg-white">
      {{ $usuarios->links() }}
    </div>
  </div>

</div>
@endsection
