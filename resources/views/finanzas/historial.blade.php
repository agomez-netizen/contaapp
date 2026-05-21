@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 mb-1 fw-bold">Historial Financiero De Proyectos</h1>
            <p class="text-muted mb-0">Movimientos registrados del sistema</p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('finanzas.exportar', request()->query()) }}" class="btn btn-success">
                Exportar Excel
            </a>

            <a href="{{ route('finanzas.index') }}" class="btn btn-primary">
                Nuevo Movimiento
            </a>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white">
            <h4 class="mb-0 fw-bold">Filtros</h4>
        </div>

        <div class="card-body">
            <form method="GET" action="{{ route('finanzas.historial') }}">

                <div class="row g-3">

                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Movimiento</label>
                        <select name="tipo_movimiento" class="form-select">
                            <option value="">Todos</option>
                            <option value="INGRESO" {{ request('tipo_movimiento') == 'INGRESO' ? 'selected' : '' }}>Ingreso</option>
                            <option value="EGRESO" {{ request('tipo_movimiento') == 'EGRESO' ? 'selected' : '' }}>Egreso</option>
                        </select>
                    </div>

                <div class="col-md-2">
                    <label class="form-label fw-semibold">Tipo Documento</label>

                    <div class="dropdown">
                        <button class="form-select text-start"
                                type="button"
                                data-bs-toggle="dropdown"
                                aria-expanded="false">
                            Tipo Documento
                        </button>

                        <div class="dropdown-menu p-3" style="min-width: 260px;">

                            @php
                                $tiposSeleccionados = request('tipo_documento', []);
                                if (!is_array($tiposSeleccionados)) {
                                    $tiposSeleccionados = [$tiposSeleccionados];
                                }

                                $tiposDocumento = [
                                    'FACTURA' => 'Factura',
                                    'RECIBO DE DONACION' => 'Recibo de Donación',
                                    'COTIZACION' => 'Cotización',
                                    'PRESUPUESTO' => 'Presupuesto',
                                    'TRANSFERENCIA' => 'Transferencia',
                                    'CARTA' => 'Carta',
                                    'OTRO' => 'Otro',
                                ];
                            @endphp

                            @foreach($tiposDocumento as $valor => $texto)

                                <div class="form-check mb-2">
                                    <input class="form-check-input"
                                        type="checkbox"
                                        name="tipo_documento[]"
                                        value="{{ $valor }}"
                                        id="tipo_{{ $loop->index }}"
                                        {{ in_array($valor, $tiposSeleccionados) ? 'checked' : '' }}>

                                    <label class="form-check-label"
                                        for="tipo_{{ $loop->index }}">
                                        {{ $texto }}
                                    </label>
                                </div>

                            @endforeach

                        </div>
                    </div>
                </div>

                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Proyecto</label>
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
                        <label class="form-label fw-semibold">Subproyecto</label>
                        <select name="id_subproyecto" class="form-select">
                            <option value="">Todos</option>
                            @foreach($subproyectos as $subproyecto)
                                <option value="{{ $subproyecto->id_subproyecto }}" {{ request('id_subproyecto') == $subproyecto->id_subproyecto ? 'selected' : '' }}>
                                    {{ $subproyecto->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Rubro</label>
                        <select name="id_rubro" class="form-select">
                            <option value="">Todos</option>
                            @foreach($rubros as $rubro)
                                <option value="{{ $rubro->id_rubro }}" {{ request('id_rubro') == $rubro->id_rubro ? 'selected' : '' }}>
                                    {{ $rubro->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>

                <div class="row g-3 mt-1">
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Desde</label>
                        <input type="date" name="fecha_inicio" value="{{ request('fecha_inicio') }}" class="form-control">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Hasta</label>
                        <input type="date" name="fecha_fin" value="{{ request('fecha_fin') }}" class="form-control">
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button class="btn btn-primary px-4">Filtrar</button>
                    <a href="{{ route('finanzas.historial') }}" class="btn btn-secondary px-4">Limpiar</a>
                </div>

            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">

        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h2 class="h3 mb-0 fw-bold">Resultados</h2>

            <div class="d-flex gap-3 align-items-center">
                <span class="fw-bold text-success">
                    Total Q: Q {{ number_format($totalQuetzales ?? 0, 2) }}
                </span>

                <span class="fw-bold text-primary">
                    Total $: $ {{ number_format($totalDolares ?? 0, 2) }}
                </span>

                <span class="badge bg-primary">
                    {{ $movimientos->total() }} registros
                </span>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">
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
                            <th>Monto Q</th>
                            <th>Monto $</th>

                            @if(session('user.id_rol') == 1)
                                <th>Acciones</th>
                            @endif
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($movimientos as $mov)
                            <tr style="cursor:pointer"
                                data-bs-toggle="modal"
                                data-bs-target="#detalleModal{{ $mov->id_movimiento }}">

                                <td>
                                    <span class="badge {{ $mov->tipo_movimiento == 'EGRESO' ? 'bg-danger' : 'bg-success' }}">
                                        {{ $mov->tipo_movimiento }}
                                    </span>
                                </td>

                                <td>{{ $mov->tipo_documento }}</td>
                                <td>{{ $mov->no_documento ?: 'N/A' }}</td>
                                <td>{{ \Carbon\Carbon::parse($mov->fecha_documento)->format('d/m/Y') }}</td>
                                <td>{{ $mov->proyecto->nombre ?? 'N/A' }}</td>
                                <td>{{ $mov->subproyecto->nombre ?? 'N/A' }}</td>
                                <td>{{ $mov->rubro->nombre ?? 'N/A' }}</td>

                                <td>
                                    <div class="fw-semibold">{{ $mov->empresa }}</div>
                                    <small class="text-muted">{{ $mov->proveedor }}</small>
                                </td>

                                <td>Q {{ number_format($mov->monto_quetzales ?? $mov->monto, 2) }}</td>
                                <td>$ {{ number_format($mov->monto_dolares ?? 0, 2) }}</td>

                                @if(session('user.id_rol') == 1)
                                    <td onclick="event.stopPropagation()">
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('finanzas.edit', $mov->id_movimiento) }}"
                                               class="btn btn-warning btn-sm"
                                               title="Editar">
                                                ✏️
                                            </a>

                                            <form method="POST"
                                                  action="{{ route('finanzas.destroy', $mov->id_movimiento) }}"
                                                  onsubmit="return confirm('¿Eliminar registro?')">
                                                @csrf
                                                @method('DELETE')

                                                <button class="btn btn-danger btn-sm" title="Eliminar">
                                                    🗑️
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                @endif
                            </tr>

                            <div class="modal fade" id="detalleModal{{ $mov->id_movimiento }}" tabindex="-1">
                                <div class="modal-dialog modal-xl modal-dialog-scrollable" style="margin-top: 90px;">
                                    <div class="modal-content">

                                        <div class="modal-header py-3">
                                            <h5 class="modal-title mb-0">Detalle Movimiento Financiero</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                        </div>

                                        <div class="modal-body">
                                            <div class="row g-4">

                                                <div class="col-md-3">
                                                    <strong>Movimiento</strong>
                                                    <div>{{ $mov->tipo_movimiento }}</div>
                                                </div>

                                                <div class="col-md-3">
                                                    <strong>Tipo Documento</strong>
                                                    <div>{{ $mov->tipo_documento }}</div>
                                                </div>

                                                <div class="col-md-3">
                                                    <strong>No. Documento</strong>
                                                    <div>{{ $mov->no_documento ?: 'N/A' }}</div>
                                                </div>

                                                <div class="col-md-3">
                                                    <strong>Fecha Documento</strong>
                                                    <div>{{ \Carbon\Carbon::parse($mov->fecha_documento)->format('d/m/Y') }}</div>
                                                </div>

                                                <div class="col-md-4">
                                                    <strong>Proyecto</strong>
                                                    <div>{{ $mov->proyecto->nombre ?? 'N/A' }}</div>
                                                </div>

                                                <div class="col-md-4">
                                                    <strong>Subproyecto</strong>
                                                    <div>{{ $mov->subproyecto->nombre ?? 'N/A' }}</div>
                                                </div>

                                                <div class="col-md-4">
                                                    <strong>Rubro</strong>
                                                    <div>{{ $mov->rubro->nombre ?? 'N/A' }}</div>
                                                </div>

                                                <div class="col-md-6">
                                                    <strong>Empresa</strong>
                                                    <div>{{ $mov->empresa ?? 'N/A' }}</div>
                                                </div>

                                                <div class="col-md-6">
                                                    <strong>Proveedor</strong>
                                                    <div>{{ $mov->proveedor ?? 'N/A' }}</div>
                                                </div>

                                                <div class="col-md-4">
                                                    <strong>Monto Quetzales</strong>
                                                    <div>Q {{ number_format($mov->monto_quetzales ?? $mov->monto, 2) }}</div>
                                                </div>

                                                <div class="col-md-4">
                                                    <strong>Monto Dólares</strong>
                                                    <div>$ {{ number_format($mov->monto_dolares ?? 0, 2) }}</div>
                                                </div>

                                                <div class="col-md-4">
                                                    <strong>Tipo Cambio</strong>
                                                    <div>{{ number_format($mov->tipo_cambio ?? 0, 4) }}</div>
                                                </div>

                                                <div class="col-md-12">
                                                    <strong>Descripción</strong>
                                                    <div class="border rounded p-3 mt-2 bg-light">
                                                        {{ $mov->descripcion ?? 'Sin descripción' }}
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <strong>Link Drive</strong>
                                                    <div class="mt-2">
                                                        @if($mov->link_drive)
                                                            <a href="{{ $mov->link_drive }}" target="_blank">
                                                                {{ $mov->link_drive }}
                                                            </a>
                                                        @else
                                                            <span class="text-muted">Sin link</span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <strong>Archivo</strong>
                                                    <div class="mt-2">
                                                        @if($mov->archivo_path)
                                                            <a href="{{ asset('storage/'.$mov->archivo_path) }}"
                                                               target="_blank"
                                                               class="btn btn-outline-primary">
                                                                Ver Archivo
                                                            </a>

                                                            <div class="small text-muted mt-1">
                                                                {{ $mov->archivo_original }}
                                                            </div>
                                                        @else
                                                            <span class="text-muted">Sin archivo</span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <strong>Usuario</strong>
                                                    <div>{{ $mov->usuario->nombre ?? 'N/A' }}</div>
                                                </div>

                                                <div class="col-md-4">
                                                    <strong>Fecha Registro</strong>
                                                    <div>{{ $mov->created_at?->format('d/m/Y H:i') }}</div>
                                                </div>

                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        @empty
                            <tr>
                                <td colspan="20" class="text-center py-4 text-muted">
                                    No existen registros
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="p-3">
                    {{ $movimientos->links() }}
                </div>

            </div>
        </div>
    </div>

</div>

@endsection
