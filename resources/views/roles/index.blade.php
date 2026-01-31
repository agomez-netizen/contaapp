@extends('layouts.app')
@section('title','Roles')

@section('content')
<div class="container py-4">

  <div class="d-flex flex-wrap gap-2 align-items-center justify-content-between mb-3">
    <div>
      <h3 class="fw-bold mb-0">🛡️ Roles</h3>
      <div class="text-muted">Mantenimiento</div>
    </div>
    <a href="{{ route('roles.create') }}" class="btn btn-primary">+ Nuevo rol</a>
  </div>

  @if(session('ok'))
    <div class="alert alert-success">{{ session('ok') }}</div>
  @endif
  @if(session('err'))
    <div class="alert alert-danger">{{ session('err') }}</div>
  @endif

  <form class="row g-2 mb-3" method="GET" action="{{ route('roles.index') }}">
    <div class="col-sm-8 col-md-6 col-lg-4">
      <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Buscar por nombre...">
    </div>
    <div class="col-auto">
      <button class="btn btn-outline-secondary">Buscar</button>
      <a href="{{ route('roles.index') }}" class="btn btn-link">Limpiar</a>
    </div>
  </form>

  <div class="card shadow-sm">
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Usuarios</th>
            <th class="text-end">Acciones</th>
          </tr>
        </thead>
        <tbody>
          @forelse($roles as $r)
            <tr>
              <td>{{ $r->id_rol }}</td>
              <td class="fw-semibold">{{ $r->nombre }}</td>
              <td><span class="badge bg-info">{{ $r->usuarios_count }}</span></td>
              <td class="text-end">
                <a class="btn btn-sm btn-outline-primary" href="{{ route('roles.show',$r) }}">Ver</a>
                <a class="btn btn-sm btn-outline-warning" href="{{ route('roles.edit',$r) }}">Editar</a>

                <form class="d-inline"
                      action="{{ route('roles.destroy',$r) }}"
                      method="POST"
                      onsubmit="return confirm('¿Eliminar este rol?')">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="4" class="text-center text-muted py-4">No hay roles.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <div class="mt-3">{{ $roles->links() }}</div>
</div>
@endsection
