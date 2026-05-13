@extends('layouts.app')

@section('content')

<div class="container-fluid py-3">

    <div class="mb-4">
        <h3 class="mb-0">Subproyectos</h3>
        <small class="text-muted">Registro de subproyectos por proyecto</small>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>
        </div>
    @endif

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-white">
            <h5 class="mb-0">Nuevo Subproyecto</h5>
        </div>

        <div class="card-body">

            <form action="{{ route('subproyectos.store') }}" method="POST">
                @csrf

                <div class="row g-3">

                    <div class="col-md-4">
                        <label class="form-label">Proyecto</label>

                        <select name="id_proyecto" class="form-select" required>
                            <option value="">Seleccione</option>

                            @foreach($proyectos as $proyecto)
                                <option value="{{ $proyecto->id_proyecto }}">
                                    {{ $proyecto->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Nombre Subproyecto</label>

                        <input type="text"
                               name="nombre"
                               class="form-control"
                               placeholder="Ej. Equipamiento"
                               required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Estado</label>

                        <select name="activo" class="form-select">
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Descripción</label>

                        <textarea name="descripcion"
                                  class="form-control"
                                  rows="3"
                                  placeholder="Descripción del subproyecto"></textarea>
                    </div>

                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">
                            Guardar Subproyecto
                        </button>
                    </div>

                </div>

            </form>

        </div>

    </div>

    <div class="card shadow-sm border-0">

        <div class="card-header bg-white">
            <h5 class="mb-0">Listado de Subproyectos</h5>
        </div>

        <div class="card-body table-responsive">

            <table class="table table-bordered table-hover align-middle">

                <thead class="table-light">
                    <tr>
                        <th>Proyecto</th>
                        <th>Subproyecto</th>
                        <th>Descripción</th>
                        <th>Estado</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($subproyectos as $subproyecto)

                        <tr>
                            <td>{{ $subproyecto->proyecto->nombre ?? 'N/A' }}</td>
                            <td>{{ $subproyecto->nombre }}</td>
                            <td>{{ $subproyecto->descripcion }}</td>
                            <td>
                                @if($subproyecto->activo)
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-secondary">Inactivo</span>
                                @endif
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                No hay subproyectos registrados.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection
