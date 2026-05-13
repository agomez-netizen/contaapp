@extends('layouts.app')

@section('content')

<div class="container-fluid py-3">

<div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="mb-0">
                Historial Financiero
            </h3>

            <small class="text-muted">
                Movimientos registrados del sistema
            </small>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route('finanzas.exportar', request()->query()) }}"
            class="btn btn-success">

                <i class="fas fa-file-excel me-1"></i>
                Exportar Excel

            </a>

            <a href="{{ route('finanzas.index') }}"
            class="btn btn-primary">

                <i class="fas fa-plus me-1"></i>
                Nuevo Movimiento

            </a>

        </div>

    </div>

    <form method="GET"
          action="{{ route('finanzas.historial') }}"
          class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-white">
            <h5 class="mb-0">Filtros</h5>
        </div>

        <div class="card-body">
            <div class="row g-3">

                <div class="col-md-2">
                    <label class="form-label">Movimiento</label>
                    <select name="tipo_movimiento" class="form-select">
                        <option value="">Todos</option>
                        <option value="INGRESO" {{ request('tipo_movimiento') == 'INGRESO' ? 'selected' : '' }}>Ingreso</option>
                        <option value="EGRESO" {{ request('tipo_movimiento') == 'EGRESO' ? 'selected' : '' }}>Egreso</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Proyecto</label>
                    <select name="id_proyecto" class="form-select">
                        <option value="">Todos</option>
                        @foreach($proyectos as $proyecto)
                            <option value="{{ $proyecto->id_proyecto }}" {{ request('id_proyecto') == $proyecto->id_proyecto ? 'selected' : '' }}>
                                {{ $proyecto->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Rubro</label>
                    <select name="id_rubro" class="form-select">
                        <option value="">Todos</option>
                        @foreach($rubros as $rubro)
                            <option value="{{ $rubro->id_rubro }}" {{ request('id_rubro') == $rubro->id_rubro ? 'selected' : '' }}>
                                {{ $rubro->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Desde</label>
                    <input type="date"
                           name="fecha_inicio"
                           value="{{ request('fecha_inicio') }}"
                           class="form-control">
                </div>

                <div class="col-md-2">
                    <label class="form-label">Hasta</label>
                    <input type="date"
                           name="fecha_fin"
                           value="{{ request('fecha_fin') }}"
                           class="form-control">
                </div>

                <div class="col-md-12 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        Filtrar
                    </button>

                    <a href="{{ route('finanzas.historial') }}" class="btn btn-secondary">
                        Limpiar
                    </a>

                </div>

            </div>
        </div>

    </form>

    <div class="card shadow-sm border-0">

        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Resultados</h5>

            <span class="badge bg-primary">
                {{ count($movimientos) }} registros
            </span>
        </div>

        <div class="card-body table-responsive">

            <table class="table table-hover table-bordered align-middle">

                <thead class="table-light">
                    <tr>
                        <th>Movimiento</th>
                        <th>Tipo Documento</th>
                        <th>No. Documento</th>
                        <th>Fecha</th>
                        <th>Proyecto</th>
                        <th>Subproyecto</th>
                        <th>Rubro</th>
                        <th>Empresa / Proveedor</th>
                        <th>Monto</th>
                        <th>Archivo</th>
                        <th>Usuario</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($movimientos as $mov)
                        <tr>
                            <td>
                                @if($mov->tipo_movimiento == 'INGRESO')
                                    <span class="badge bg-success">INGRESO</span>
                                @else
                                    <span class="badge bg-danger">EGRESO</span>
                                @endif
                            </td>

                            <td>{{ $mov->tipo_documento }}</td>
                            <td>{{ $mov->no_documento }}</td>
                            <td>{{ \Carbon\Carbon::parse($mov->fecha_documento)->format('d/m/Y') }}</td>
                            <td>{{ $mov->proyecto->nombre ?? 'N/A' }}</td>
                            <td>{{ $mov->subproyecto->nombre ?? 'N/A' }}</td>
                            <td>{{ $mov->rubro->nombre ?? 'N/A' }}</td>
                            <td>{{ $mov->empresa ?? $mov->proveedor }}</td>
                            <td>Q {{ number_format($mov->monto, 2) }}</td>

                            <td>
                                @if($mov->archivo_path)
                                    <a href="{{ asset('storage/' . $mov->archivo_path) }}"
                                       target="_blank"
                                       class="btn btn-sm btn-outline-primary">
                                        Ver Archivo
                                    </a>
                                @else
                                    <span class="text-muted">Sin archivo</span>
                                @endif
                            </td>

                            <td>{{ $mov->usuario->nombre ?? 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center text-muted py-4">
                                No existen movimientos financieros registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>

        </div>
    </div>

</div>

@endsection
