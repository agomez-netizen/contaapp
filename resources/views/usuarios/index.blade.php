@extends('layouts.app')
@section('title','Usuarios')

@section('content')
<div class="container py-4">

  <div class="d-flex flex-wrap gap-2 align-items-center justify-content-between mb-3">
    <div>
      <h3 class="fw-bold mb-0">👤 Usuarios</h3>
      <div class="text-muted">Mantenimiento</div>
    </div>
    <a href="{{ route('usuarios.create') }}" class="btn btn-primary">+ Nuevo usuario</a>
  </div>

  @if(session('ok'))
    <div class="alert alert-success">{{ session('ok') }}</div>
  @endif

  <form class="row g-2 mb-3" method="GET" action="{{ route('usuarios.index') }}">
    <div class="col-sm-8 col-md-6 col-lg-4">
      <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Buscar por nombre, apellido o usuario...">
    </div>
    <div class="col-auto">
      <button class="btn btn-outline-secondary">Buscar</button>
      <a href="{{ route('usuarios.index') }}" class="btn btn-link">Limpiar</a>
    </div>
  </form>

  <div class="card shadow-sm">
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Usuario</th>
            <th>Rol</th>
            <th>Estado</th>
            <th class="text-end">Acciones</th>
          </tr>
        </thead>
        <tbody>
          @forelse($usuarios as $u)
            <tr>
              <td>{{ $u->id_usuario }}</td>
              <td class="fw-semibold">{{ $u->nombre }} {{ $u->apellido }}</td>
              <td>{{ $u->usuario }}</td>
              <td>{{ $u->rol->nombre ?? '—' }}</td>
              <td>
                @if($u->estado)
                  <span class="badge bg-success">Activo</span>
                @else
                  <span class="badge bg-secondary">Inactivo</span>
                @endif
              </td>
              <td class="text-end">
                <a class="btn btn-sm btn-outline-primary" href="{{ route('usuarios.show',$u) }}">Ver</a>
                <a class="btn btn-sm btn-outline-warning" href="{{ route('usuarios.edit',$u) }}">Editar</a>

                <form class="d-inline"
                      action="{{ route('usuarios.destroy',$u) }}"
                      method="POST"
                      onsubmit="return confirm('¿Eliminar este usuario?')">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-center text-muted py-4">No hay usuarios.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <div class="mt-3">{{ $usuarios->links() }}</div>
</div>
@endsection
