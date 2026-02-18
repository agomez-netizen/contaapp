@extends('layouts.app')

@section('title', 'Contactos')

@section('content')
<div class="container py-4">

  @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
  @endif

<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <h3 class="fw-bold mb-0">📇 Contactos</h3>
    <div class="text-muted">Listado de contactos por proyecto</div>
  </div>

  <div class="d-flex gap-2">
    <a href="{{ route('contactos.export.excel', request()->query()) }}"
       class="btn btn-success">
      📊 Exportar Excel
    </a>

    <a href="{{ route('contactos.create') }}" class="btn btn-primary">
      + Nuevo contacto
    </a>
  </div>
</div>


  <div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
      <form class="row g-2" method="GET" action="{{ route('contactos.index') }}">
        <div class="col-12 col-md-4">
          <input type="text" class="form-control" name="q" value="{{ $q ?? '' }}" placeholder="Buscar: nombre, correo, teléfono, NIT, motivo...">
        </div>

        <div class="col-12 col-md-4">
          <select class="form-select" name="proyecto">
            <option value="0">— Todos los proyectos —</option>
            @foreach($proyectos as $p)
              <option value="{{ $p->id_proyecto }}" {{ ((int)($proyecto ?? 0) === (int)$p->id_proyecto) ? 'selected' : '' }}>
                {{ $p->nombre }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="col-12 col-md-2">
          <select class="form-select" name="tipo">
            <option value="">— Tipo —</option>
            @foreach(['Empresa','Fundacion','Persona','ONG'] as $t)
              <option value="{{ $t }}" {{ (($tipo ?? '') === $t) ? 'selected' : '' }}>{{ $t }}</option>
            @endforeach
          </select>
        </div>

        <div class="col-12 col-md-2 d-grid">
          <button class="btn btn-outline-secondary">Filtrar</button>
        </div>
      </form>
    </div>
  </div>

  <div class="card border-0 shadow-sm">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>Tipo</th>
            <th>Nombre</th>
            <th>Contacto</th>
            <th>Teléfono</th>
            <th>Ext.</th>
            <th>Correo</th>
            <th style="width:140px;"></th>
          </tr>
        </thead>
        <tbody>
          @forelse($contactos as $c)
            <tr style="cursor:pointer" onclick="window.location='{{ route('contactos.show', $c->id_contacto) }}'">
              <td>{{ $c->tipo }}</td>
              <td class="fw-semibold">{{ $c->nombre }}</td>
              <td>{{ $c->contacto ?? '—' }}</td>
              <td>{{ $c->telefono ?? '—' }}</td>
              <td>{{ $c->extension ?? '—' }}</td>
              <td>{{ $c->correo ?? '—' }}</td>
              <td class="text-end" onclick="event.stopPropagation()">
                <a href="{{ route('contactos.edit', $c->id_contacto) }}" class="btn btn-sm btn-outline-primary">✏️</a>
                <form action="{{ route('contactos.destroy', $c->id_contacto) }}" method="POST" class="d-inline"
                      onsubmit="return confirm('¿Eliminar este contacto?');">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-sm btn-outline-danger">🗑️</button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="9" class="text-center text-muted py-4">No hay contactos.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if($contactos->hasPages())
      <div class="card-body">
        {{ $contactos->links() }}
      </div>
    @endif
  </div>

</div>
@endsection
