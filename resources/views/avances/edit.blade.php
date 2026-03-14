@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-10">
      @if(session('success'))
        <div class="alert alert-success">
          {{ session('success') }}
        </div>
      @endif

      @if ($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <div class="card shadow-sm mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h4 class="mb-0">Editar avance</h4>
          <a href="{{ route('avances.byDate') }}" class="btn btn-secondary btn-sm">
            Volver
          </a>
        </div>

        <div class="card-body">
          <form action="{{ route('avances.update', $avance->id_avance) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
              <label for="id_proyecto" class="form-label">Proyecto</label>
              <select name="id_proyecto" id="id_proyecto" class="form-select" required>
                <option value="">Seleccione un proyecto</option>
                @foreach($proyectos as $p)
                  <option value="{{ $p->id_proyecto }}"
                    @selected(old('id_proyecto', $avance->id_proyecto) == $p->id_proyecto)>
                    {{ $p->nombre }}
                  </option>
                @endforeach
              </select>
            </div>

            <div class="mb-3">
              <label for="fecha" class="form-label">Fecha</label>
              <input
                type="date"
                name="fecha"
                id="fecha"
                class="form-control"
                value="{{ old('fecha', optional($avance->fecha)->format('Y-m-d')) }}"
                required
              >
            </div>

            <div class="mb-3">
              <label for="descripcion" class="form-label">Descripción</label>
              <textarea
                name="descripcion"
                id="descripcion"
                rows="7"
                class="form-control"
                required
              >{{ old('descripcion', trim(strip_tags($avance->descripcion))) }}</textarea>
            </div>

            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-primary">
                💾 Guardar cambios
              </button>

              <a href="{{ route('avances.byDate') }}" class="btn btn-outline-secondary">
                Cancelar
              </a>
            </div>
          </form>
        </div>
      </div>

      <div class="card shadow-sm">
        <div class="card-header">
          <h5 class="mb-0">Historial de cambios</h5>
        </div>
        <div class="card-body">
          @forelse($avance->historial as $h)
            <div class="border rounded p-3 mb-3">
              <div class="mb-2">
                <strong>Fecha de edición:</strong>
                {{ optional($h->created_at)->format('d/m/Y H:i') }}
              </div>

              <div class="mb-2">
                <strong>Editado por:</strong>
                @if($h->editor)
                  {{ $h->editor->nombre }} {{ $h->editor->apellido }}
                @else
                  Usuario no disponible
                @endif
              </div>

              <div class="mb-2">
                <strong>Proyecto anterior:</strong>
                {{ \App\Models\Proyecto::find($h->id_proyecto_anterior)->nombre ?? '—' }}
                <br>

                <strong>Proyecto nuevo:</strong>
                {{ \App\Models\Proyecto::find($h->id_proyecto_nuevo)->nombre ?? '—' }}
              </div>

              <div class="mb-2">
                <strong>Fecha anterior:</strong>
                {{ $h->fecha_anterior ? \Carbon\Carbon::parse($h->fecha_anterior)->format('d/m/Y') : '—' }}
                <br>

                <strong>Fecha nueva:</strong>
                {{ $h->fecha_nueva ? \Carbon\Carbon::parse($h->fecha_nueva)->format('d/m/Y') : '—' }}
              </div>

              <div class="mb-2">
                <strong>Descripción anterior:</strong>
                <div class="border rounded p-2 bg-light">
                  {!! $h->descripcion_anterior ?: '—' !!}
                </div>
              </div>

              <div>
                <strong>Descripción nueva:</strong>
                <div class="border rounded p-2 bg-light">
                  {!! $h->descripcion_nueva ?: '—' !!}
                </div>
              </div>
            </div>
          @empty
            <div class="alert alert-secondary mb-0">
              Este avance todavía no tiene cambios registrados.
            </div>
          @endforelse
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
