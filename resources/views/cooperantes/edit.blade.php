@extends('layouts.app')

@section('content')

@php
    $conv = $organizacion->convocatorias->first();
@endphp

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3>Editar Cooperante</h3>
            <p class="text-muted">{{ $organizacion->nombre }}</p>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" form="formEditarCooperante" class="btn btn-primary">
                Actualizar Cooperante
            </button>

            <a href="{{ route('cooperantes.index') }}" class="btn btn-secondary">
                Volver
            </a>
        </div>
    </div>

    <form id="formEditarCooperante" action="{{ route('cooperantes.update', $organizacion->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                Información de la organización
            </div>

            <div class="card-body">
                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label>Nombre *</label>
                        <input type="text" name="nombre" class="form-control" required value="{{ old('nombre', $organizacion->nombre) }}">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>Tipo</label>
                        <input type="text" name="tipo_organizacion" class="form-control" value="{{ old('tipo_organizacion', $organizacion->tipo_organizacion) }}">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>País</label>
                        <input type="text" name="pais" class="form-control" value="{{ old('pais', $organizacion->pais) }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Dirección</label>
                        <input type="text" name="direccion" class="form-control" value="{{ old('direccion', $organizacion->direccion) }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Correo general</label>
                        <input type="email" name="correo_general" class="form-control" value="{{ old('correo_general', $organizacion->correo_general) }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Área de apoyo</label>
                        <input type="text" name="area_apoyo" class="form-control" value="{{ old('area_apoyo', $organizacion->area_apoyo) }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Enfoque geográfico</label>
                        <input type="text" name="enfoque_geografico" class="form-control" value="{{ old('enfoque_geografico', $organizacion->enfoque_geografico) }}">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Idioma</label>
                        <input type="text" name="idioma_comunicacion" class="form-control" value="{{ old('idioma_comunicacion', $organizacion->idioma_comunicacion) }}">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Monto estimado</label>
                        <input type="text" name="monto_estimado" class="form-control" value="{{ old('monto_estimado', $organizacion->monto_estimado) }}">
                    </div>

                    <div class="col-md-2 mb-3">
                        <label>Prioridad</label>
                        <select name="prioridad" class="form-control">
                            <option value="Alta" {{ $organizacion->prioridad == 'Alta' ? 'selected' : '' }}>Alta</option>
                            <option value="Media" {{ $organizacion->prioridad == 'Media' ? 'selected' : '' }}>Media</option>
                            <option value="Baja" {{ $organizacion->prioridad == 'Baja' ? 'selected' : '' }}>Baja</option>
                        </select>
                    </div>

                    <div class="col-md-2 mb-3">
                        <label>Estado</label>
                        <select name="estado" class="form-control">
                            @foreach(['Identificada','Contacto inicial','En seguimiento','Aplicado','Aprobado','Rechazado','Inactiva'] as $estado)
                                <option value="{{ $estado }}" {{ $organizacion->estado == $estado ? 'selected' : '' }}>
                                    {{ $estado }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label>Descripción</label>
                        <textarea name="descripcion" class="form-control" rows="3">{{ old('descripcion', $organizacion->descripcion) }}</textarea>
                    </div>

                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-danger text-white">
                Información de la convocatoria
            </div>

            <div class="card-body">
                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label>Nombre de la convocatoria</label>
                        <input type="text" name="convocatoria[nombre]" class="form-control" value="{{ old('convocatoria.nombre', $conv->nombre ?? '') }}">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>Tipo de apoyo</label>
                        <input type="text" name="convocatoria[tipo_apoyo]" class="form-control" value="{{ old('convocatoria.tipo_apoyo', $conv->tipo_apoyo ?? '') }}">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>Periodicidad</label>
                        <input type="text" name="convocatoria[periodicidad]" class="form-control" value="{{ old('convocatoria.periodicidad', $conv->periodicidad ?? '') }}">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>Fecha de apertura</label>
                        <input type="date" name="convocatoria[fecha_apertura]" class="form-control" value="{{ old('convocatoria.fecha_apertura', $conv->fecha_apertura ?? '') }}">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>Fecha límite para aplicar</label>
                        <input type="date" name="convocatoria[fecha_cierre]" class="form-control" value="{{ old('convocatoria.fecha_cierre', $conv->fecha_cierre ?? '') }}">
                    </div>

                    <div class="col-md-2 mb-3">
                        <label>Monto mínimo</label>
                        <input type="number" step="0.01" name="convocatoria[monto_minimo]" class="form-control" value="{{ old('convocatoria.monto_minimo', $conv->monto_minimo ?? '') }}">
                    </div>

                    <div class="col-md-2 mb-3">
                        <label>Monto máximo</label>
                        <input type="number" step="0.01" name="convocatoria[monto_maximo]" class="form-control" value="{{ old('convocatoria.monto_maximo', $conv->monto_maximo ?? '') }}">
                    </div>

                    <div class="col-md-2 mb-3">
                        <label>Moneda</label>
                        <select name="convocatoria[moneda]" class="form-control">
                            @foreach(['USD','EUR','GTQ'] as $moneda)
                                <option value="{{ $moneda }}" {{ ($conv->moneda ?? 'USD') == $moneda ? 'selected' : '' }}>
                                    {{ $moneda }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Correo para alerta</label>
                        <input type="email" name="convocatoria[correo_alerta]" class="form-control" value="{{ old('convocatoria.correo_alerta', $conv->correo_alerta ?? '') }}">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>Alerta 7 días antes</label>
                        <select name="convocatoria[alerta_7_dias]" class="form-control">
                            <option value="1" {{ ($conv->alerta_7_dias ?? 1) == 1 ? 'selected' : '' }}>Activada</option>
                            <option value="0" {{ ($conv->alerta_7_dias ?? 1) == 0 ? 'selected' : '' }}>Desactivada</option>
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>Estado convocatoria</label>
                        <select name="convocatoria[estado]" class="form-control">
                            @foreach(['Pendiente','Activa','Cerrada','Aplicada','Descartada'] as $estado)
                                <option value="{{ $estado }}" {{ ($conv->estado ?? 'Pendiente') == $estado ? 'selected' : '' }}>
                                    {{ $estado }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Enlace de aplicación</label>
                        <input type="url" name="convocatoria[enlace]" class="form-control" value="{{ old('convocatoria.enlace', $conv->enlace ?? '') }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Áreas prioritarias</label>
                        <textarea name="convocatoria[areas_prioritarias]" class="form-control" rows="2">{{ old('convocatoria.areas_prioritarias', $conv->areas_prioritarias ?? '') }}</textarea>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Requisitos clave</label>
                        <textarea name="convocatoria[requisitos_clave]" class="form-control" rows="2">{{ old('convocatoria.requisitos_clave', $conv->requisitos_clave ?? '') }}</textarea>
                    </div>

                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-success text-white d-flex justify-content-between">
                <span>Contactos</span>
                <button type="button" class="btn btn-light btn-sm" onclick="agregarContacto()">+ Agregar</button>
            </div>

            <div class="card-body" id="contactosContainer">

                @foreach($organizacion->contactos as $i => $contacto)
                    <div class="border rounded p-3 mb-3 contacto-item">
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.contacto-item').remove()">Eliminar</button>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label>Nombre</label>
                                <input type="text" name="contactos[{{ $i }}][nombre]" class="form-control" value="{{ $contacto->nombre }}">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label>Cargo</label>
                                <input type="text" name="contactos[{{ $i }}][cargo]" class="form-control" value="{{ $contacto->cargo }}">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label>Correo</label>
                                <input type="email" name="contactos[{{ $i }}][correo]" class="form-control" value="{{ $contacto->correo }}">
                            </div>

                            <div class="col-md-3 mb-3">
                                <label>Teléfono</label>
                                <input type="text" name="contactos[{{ $i }}][telefono]" class="form-control" value="{{ $contacto->telefono }}">
                            </div>

                            <div class="col-md-3 mb-3">
                                <label>WhatsApp</label>
                                <input type="text" name="contactos[{{ $i }}][whatsapp]" class="form-control" value="{{ $contacto->whatsapp }}">
                            </div>

                            <div class="col-md-3 mb-3">
                                <label>Idioma</label>
                                <input type="text" name="contactos[{{ $i }}][idioma]" class="form-control" value="{{ $contacto->idioma }}">
                            </div>

                            <div class="col-md-3 mb-3">
                                <label>Medio preferido</label>
                                <input type="text" name="contactos[{{ $i }}][medio_preferido]" class="form-control" value="{{ $contacto->medio_preferido }}">
                            </div>

                            <div class="col-md-12 mb-3">
                                <label>Notas</label>
                                <textarea name="contactos[{{ $i }}][notas]" class="form-control" rows="2">{{ $contacto->notas }}</textarea>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-info text-white d-flex justify-content-between">
                <span>Teléfonos</span>
                <button type="button" class="btn btn-light btn-sm" onclick="agregarTelefono()">+ Agregar</button>
            </div>

            <div class="card-body" id="telefonosContainer">

                @foreach($organizacion->telefonos as $i => $tel)
                    <div class="border rounded p-3 mb-3 telefono-item">
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.telefono-item').remove()">Eliminar</button>
                        </div>

                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label>Tipo</label>
                                <input type="text" name="telefonos[{{ $i }}][tipo]" class="form-control" value="{{ $tel->tipo }}">
                            </div>

                            <div class="col-md-3 mb-3">
                                <label>Número</label>
                                <input type="text" name="telefonos[{{ $i }}][numero]" class="form-control" value="{{ $tel->numero }}">
                            </div>

                            <div class="col-md-2 mb-3">
                                <label>Extensión</label>
                                <input type="text" name="telefonos[{{ $i }}][extension]" class="form-control" value="{{ $tel->extension }}">
                            </div>

                            <div class="col-md-2 mb-3">
                                <label>País</label>
                                <input type="text" name="telefonos[{{ $i }}][pais]" class="form-control" value="{{ $tel->pais }}">
                            </div>

                            <div class="col-md-2 mb-3">
                                <label>Observaciones</label>
                                <input type="text" name="telefonos[{{ $i }}][observaciones]" class="form-control" value="{{ $tel->observaciones }}">
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-warning d-flex justify-content-between">
                <span>Sitios web</span>
                <button type="button" class="btn btn-light btn-sm" onclick="agregarWeb()">+ Agregar</button>
            </div>

            <div class="card-body" id="websContainer">

                @foreach($organizacion->webs as $i => $web)
                    <div class="border rounded p-3 mb-3 web-item">
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.web-item').remove()">Eliminar</button>
                        </div>

                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label>Tipo</label>
                                <input type="text" name="webs[{{ $i }}][tipo]" class="form-control" value="{{ $web->tipo }}">
                            </div>

                            <div class="col-md-5 mb-3">
                                <label>URL</label>
                                <input type="url" name="webs[{{ $i }}][url]" class="form-control" value="{{ $web->url }}">
                            </div>

                            <div class="col-md-3 mb-3">
                                <label>Descripción</label>
                                <input type="text" name="webs[{{ $i }}][descripcion]" class="form-control" value="{{ $web->descripcion }}">
                            </div>

                            <div class="col-md-1 mb-3">
                                <label>Activo</label>
                                <input type="checkbox" name="webs[{{ $i }}][activo]" class="form-check-input d-block mt-2" {{ $web->activo ? 'checked' : '' }}>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-dark text-white d-flex justify-content-between">
                <span>Redes sociales</span>
                <button type="button" class="btn btn-light btn-sm" onclick="agregarRed()">+ Agregar</button>
            </div>

            <div class="card-body" id="redesContainer">

                @foreach($organizacion->redes as $i => $red)
                    <div class="border rounded p-3 mb-3 red-item">
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.red-item').remove()">Eliminar</button>
                        </div>

                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label>Red social</label>
                                <input type="text" name="redes[{{ $i }}][red_social]" class="form-control" value="{{ $red->red_social }}">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label>URL</label>
                                <input type="url" name="redes[{{ $i }}][url]" class="form-control" value="{{ $red->url }}">
                            </div>

                            <div class="col-md-2 mb-3">
                                <label>Usuario</label>
                                <input type="text" name="redes[{{ $i }}][usuario]" class="form-control" value="{{ $red->usuario }}">
                            </div>

                            <div class="col-md-3 mb-3">
                                <label>Notas</label>
                                <input type="text" name="redes[{{ $i }}][notas]" class="form-control" value="{{ $red->notas }}">
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>

    </form>

</div>

<script>
let contactoIndex = {{ $organizacion->contactos->count() }};
let telefonoIndex = {{ $organizacion->telefonos->count() }};
let webIndex = {{ $organizacion->webs->count() }};
let redIndex = {{ $organizacion->redes->count() }};

function agregarContacto() {
    let html = `
    <div class="border rounded p-3 mb-3 contacto-item">
        <div class="d-flex justify-content-end">
            <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.contacto-item').remove()">Eliminar</button>
        </div>
        <div class="row">
            <div class="col-md-4 mb-3"><label>Nombre</label><input type="text" name="contactos[${contactoIndex}][nombre]" class="form-control"></div>
            <div class="col-md-4 mb-3"><label>Cargo</label><input type="text" name="contactos[${contactoIndex}][cargo]" class="form-control"></div>
            <div class="col-md-4 mb-3"><label>Correo</label><input type="email" name="contactos[${contactoIndex}][correo]" class="form-control"></div>
            <div class="col-md-3 mb-3"><label>Teléfono</label><input type="text" name="contactos[${contactoIndex}][telefono]" class="form-control"></div>
            <div class="col-md-3 mb-3"><label>WhatsApp</label><input type="text" name="contactos[${contactoIndex}][whatsapp]" class="form-control"></div>
            <div class="col-md-3 mb-3"><label>Idioma</label><input type="text" name="contactos[${contactoIndex}][idioma]" class="form-control"></div>
            <div class="col-md-3 mb-3"><label>Medio preferido</label><input type="text" name="contactos[${contactoIndex}][medio_preferido]" class="form-control"></div>
            <div class="col-md-12 mb-3"><label>Notas</label><textarea name="contactos[${contactoIndex}][notas]" class="form-control" rows="2"></textarea></div>
        </div>
    </div>`;
    document.getElementById('contactosContainer').insertAdjacentHTML('beforeend', html);
    contactoIndex++;
}

function agregarTelefono() {
    let html = `
    <div class="border rounded p-3 mb-3 telefono-item">
        <div class="d-flex justify-content-end">
            <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.telefono-item').remove()">Eliminar</button>
        </div>
        <div class="row">
            <div class="col-md-3 mb-3"><label>Tipo</label><input type="text" name="telefonos[${telefonoIndex}][tipo]" class="form-control"></div>
            <div class="col-md-3 mb-3"><label>Número</label><input type="text" name="telefonos[${telefonoIndex}][numero]" class="form-control"></div>
            <div class="col-md-2 mb-3"><label>Extensión</label><input type="text" name="telefonos[${telefonoIndex}][extension]" class="form-control"></div>
            <div class="col-md-2 mb-3"><label>País</label><input type="text" name="telefonos[${telefonoIndex}][pais]" class="form-control"></div>
            <div class="col-md-2 mb-3"><label>Observaciones</label><input type="text" name="telefonos[${telefonoIndex}][observaciones]" class="form-control"></div>
        </div>
    </div>`;
    document.getElementById('telefonosContainer').insertAdjacentHTML('beforeend', html);
    telefonoIndex++;
}

function agregarWeb() {
    let html = `
    <div class="border rounded p-3 mb-3 web-item">
        <div class="d-flex justify-content-end">
            <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.web-item').remove()">Eliminar</button>
        </div>
        <div class="row">
            <div class="col-md-3 mb-3"><label>Tipo</label><input type="text" name="webs[${webIndex}][tipo]" class="form-control"></div>
            <div class="col-md-5 mb-3"><label>URL</label><input type="url" name="webs[${webIndex}][url]" class="form-control"></div>
            <div class="col-md-3 mb-3"><label>Descripción</label><input type="text" name="webs[${webIndex}][descripcion]" class="form-control"></div>
            <div class="col-md-1 mb-3"><label>Activo</label><input type="checkbox" name="webs[${webIndex}][activo]" class="form-check-input d-block mt-2" checked></div>
        </div>
    </div>`;
    document.getElementById('websContainer').insertAdjacentHTML('beforeend', html);
    webIndex++;
}

function agregarRed() {
    let html = `
    <div class="border rounded p-3 mb-3 red-item">
        <div class="d-flex justify-content-end">
            <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.red-item').remove()">Eliminar</button>
        </div>
        <div class="row">
            <div class="col-md-3 mb-3"><label>Red social</label><input type="text" name="redes[${redIndex}][red_social]" class="form-control"></div>
            <div class="col-md-4 mb-3"><label>URL</label><input type="url" name="redes[${redIndex}][url]" class="form-control"></div>
            <div class="col-md-2 mb-3"><label>Usuario</label><input type="text" name="redes[${redIndex}][usuario]" class="form-control"></div>
            <div class="col-md-3 mb-3"><label>Notas</label><input type="text" name="redes[${redIndex}][notas]" class="form-control"></div>
        </div>
    </div>`;
    document.getElementById('redesContainer').insertAdjacentHTML('beforeend', html);
    redIndex++;
}
</script>

@endsection
