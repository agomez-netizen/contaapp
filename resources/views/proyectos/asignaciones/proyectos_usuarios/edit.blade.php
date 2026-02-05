@extends('layouts.app')

@section('title', 'Asignar Proyectos')

@section('content')
<div class="container py-4">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h4 class="mb-0">Asignar Proyectos</h4>
      <div class="text-muted">
        Usuario: <span class="fw-semibold">{{ $usuario->nombre }} {{ $usuario->apellido }}</span>
        <span class="text-muted">({{ $usuario->usuario }})</span>
      </div>
    </div>

    <a class="btn btn-outline-secondary" href="{{ route('asignaciones.proyectos_usuarios.index') }}">
      ← Volver
    </a>
  </div>

  @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
  @endif

  @if($errors->any())
    <div class="alert alert-danger">
      <div class="fw-semibold">Corrige estos errores:</div>
      <ul class="mb-0">
        @foreach($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="card border-0 shadow-sm">
    <div class="card-body">
      <form method="POST" action="{{ route('asignaciones.proyectos_usuarios.update', $usuario->id_usuario) }}">
        @csrf
        @method('PUT')

        <div class="mb-2 fw-semibold">Proyectos asignados</div>
        <div class="text-muted small mb-3">
          Marca los proyectos que este usuario puede ver en “Avances”.
        </div>

        <div class="row">
          @forelse($proyectos as $p)
            <div class="col-md-6 col-lg-4">
              <div class="form-check mb-2">
                <input class="form-check-input"
                       type="checkbox"
                       name="proyectos[]"
                       value="{{ $p->id_proyecto }}"
                       id="p{{ $p->id_proyecto }}"
                       {{ in_array($p->id_proyecto, $asignados) ? 'checked' : '' }}>
                <label class="form-check-label" for="p{{ $p->id_proyecto }}">
                  {{ $p->nombre }}
                </label>
              </div>
            </div>
          @empty
            <div class="col-12 text-muted">No hay proyectos activos.</div>
          @endforelse
        </div>

        <hr>

        <div class="d-flex gap-2">
          <button type="submit" class="btn btn-primary">Guardar</button>
          <a href="{{ route('asignaciones.proyectos_usuarios.index') }}" class="btn btn-outline-secondary">Cancelar</a>
        </div>
      </form>
    </div>
  </div>

</div>
@endsection
