@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="mb-0">
                Editar Movimiento Financiero
            </h2>

            <small class="text-muted">
                Actualización de registro financiero
            </small>
        </div>

        <a href="{{ route('finanzas.historial') }}"
           class="btn btn-secondary">

            Volver

        </a>

    </div>

    <div class="card shadow-sm border-0">

        <div class="card-header bg-white">
            <h4 class="mb-0">
                Información del Movimiento
            </h4>
        </div>

        <div class="card-body">

            <form action="{{ route('finanzas.update', $movimiento->id_movimiento) }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf
                @method('PUT')

                <div class="row g-3">

                    <div class="col-md-4">
                        <label class="form-label">Tipo Movimiento</label>

                        <select name="tipo_movimiento"
                                class="form-select"
                                required>

                            <option value="INGRESO"
                                {{ $movimiento->tipo_movimiento == 'INGRESO' ? 'selected' : '' }}>
                                Ingreso
                            </option>

                            <option value="EGRESO"
                                {{ $movimiento->tipo_movimiento == 'EGRESO' ? 'selected' : '' }}>
                                Egreso
                            </option>

                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Tipo Documento</label>

                        <select name="tipo_documento"
                                class="form-select"
                                required>

                            <option value="FACTURA"
                                {{ $movimiento->tipo_documento == 'FACTURA' ? 'selected' : '' }}>
                                Factura
                            </option>

                            <option value="RECIBO"
                                {{ $movimiento->tipo_documento == 'RECIBO' ? 'selected' : '' }}>
                                Recibo
                            </option>

                            <option value="COTIZACION"
                                {{ $movimiento->tipo_documento == 'COTIZACION' ? 'selected' : '' }}>
                                Cotización
                            </option>

                            <option value="CARTA"
                                {{ $movimiento->tipo_documento == 'CARTA' ? 'selected' : '' }}>
                                Carta
                            </option>

                            <option value="PRESUPUESTO"
                                {{ $movimiento->tipo_documento == 'PRESUPUESTO' ? 'selected' : '' }}>
                                Presupuesto
                            </option>

                            <option value="OTRO"
                                {{ $movimiento->tipo_documento == 'OTRO' ? 'selected' : '' }}>
                                Otro
                            </option>

                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">No. Documento</label>

                        <input type="text"
                               name="no_documento"
                               class="form-control"
                               value="{{ $movimiento->no_documento }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Fecha Documento</label>

                        <input type="date"
                               name="fecha_documento"
                               class="form-control"
                               value="{{ $movimiento->fecha_documento }}"
                               required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Proyecto</label>

                        <select name="id_proyecto"
                                class="form-select"
                                required>

                            @foreach($proyectos as $proyecto)

                                <option value="{{ $proyecto->id_proyecto }}"
                                    {{ $movimiento->id_proyecto == $proyecto->id_proyecto ? 'selected' : '' }}>

                                    {{ $proyecto->nombre }}

                                </option>

                            @endforeach

                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Subproyecto</label>

                        <select name="id_subproyecto"
                                class="form-select">

                            <option value="">Seleccione</option>

                            @foreach($subproyectos as $sub)

                                <option value="{{ $sub->id_subproyecto }}"
                                    {{ $movimiento->id_subproyecto == $sub->id_subproyecto ? 'selected' : '' }}>

                                    {{ $sub->nombre }}

                                </option>

                            @endforeach

                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Rubro</label>

                        <select name="id_rubro"
                                class="form-select">

                            @foreach($rubros as $rubro)

                                <option value="{{ $rubro->id_rubro }}"
                                    {{ $movimiento->id_rubro == $rubro->id_rubro ? 'selected' : '' }}>

                                    {{ $rubro->nombre }}

                                </option>

                            @endforeach

                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Empresa</label>

                        <input type="text"
                               name="empresa"
                               class="form-control"
                               value="{{ $movimiento->empresa }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Contacto</label>

                        <input type="text"
                               name="proveedor"
                               class="form-control"
                               value="{{ $movimiento->proveedor }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Tipo Cambio</label>

                        <input type="number"
                               step="0.0001"
                               name="tipo_cambio"
                               class="form-control"
                               value="{{ $movimiento->tipo_cambio }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Monto Q</label>

                        <input type="number"
                               step="0.01"
                               name="monto_quetzales"
                               class="form-control"
                               value="{{ $movimiento->monto_quetzales }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Monto $</label>

                        <input type="number"
                               step="0.01"
                               name="monto_dolares"
                               class="form-control"
                               value="{{ $movimiento->monto_dolares }}">
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Descripción</label>

                        <textarea name="descripcion"
                                  class="form-control"
                                  rows="4">{{ $movimiento->descripcion }}</textarea>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Link Drive</label>

                        <input type="url"
                               name="link_drive"
                               class="form-control"
                               value="{{ $movimiento->link_drive }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Actualizar Archivo</label>

                        <input type="file"
                               name="archivo"
                               class="form-control">
                    </div>

                    <div class="col-md-12 mt-3">

                        <button type="submit"
                                class="btn btn-primary">

                            Actualizar Movimiento

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection
