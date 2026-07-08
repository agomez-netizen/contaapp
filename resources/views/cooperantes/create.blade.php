@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3>Nuevo Cooperante</h3>
            <p class="text-muted">Registro de organización, convocatoria, contactos, teléfonos, sitios web y redes sociales.</p>
        </div>

        <a href="{{ route('cooperantes.index') }}" class="btn btn-secondary">
            Volver
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            Revisa los campos obligatorios.
        </div>
    @endif

    <form action="{{ route('cooperantes.store') }}" method="POST">
        @csrf

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                1. Información de la organización
            </div>

            <div class="card-body">
                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label>Nombre de la organización *</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>Tipo de organización</label>
                        <input type="text" name="tipo_organizacion" class="form-control" placeholder="Fundación, ONG, Empresa RSE">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>País</label>
                        <input type="text" name="pais" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Dirección</label>
                        <input type="text" name="direccion" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Correo general</label>
                        <input type="email" name="correo_general" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Área de apoyo</label>
                        <input type="text" name="area_apoyo" class="form-control" placeholder="Salud, discapacidad, educación, tecnología">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Enfoque geográfico</label>
                        <input type="text" name="enfoque_geografico" class="form-control" placeholder="Guatemala, Centroamérica, América Latina">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Idioma de comunicación</label>
                        <input type="text" name="idioma_comunicacion" class="form-control" placeholder="Español, inglés">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Monto estimado</label>
                        <input type="text" name="monto_estimado" class="form-control" placeholder="USD 10,000 - 50,000">
                    </div>

                    <div class="col-md-2 mb-3">
                        <label>Prioridad</label>
                        <select name="prioridad" class="form-control">
                            <option value="Alta">Alta</option>
                            <option value="Media" selected>Media</option>
                            <option value="Baja">Baja</option>
                        </select>
                    </div>

                    <div class="col-md-2 mb-3">
                        <label>Estado</label>
                        <select name="estado" class="form-control">
                            <option value="Identificada">Identificada</option>
                            <option value="Contacto inicial">Contacto inicial</option>
                            <option value="En seguimiento">En seguimiento</option>
                            <option value="Aplicado">Aplicado</option>
                            <option value="Aprobado">Aprobado</option>
                            <option value="Rechazado">Rechazado</option>
                            <option value="Inactiva">Inactiva</option>
                        </select>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label>Descripción / interés para OSSHP</label>
                        <textarea name="descripcion" class="form-control" rows="3"></textarea>
                    </div>

                </div>
            </div>
        </div>


        <div class="card shadow-sm mb-4">
            <div class="card-header bg-danger text-white">
                2. Información de la convocatoria
            </div>

            <div class="card-body">
                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label>Nombre de la convocatoria</label>
                        <input type="text" name="convocatoria[nombre]" class="form-control" placeholder="Ej. Fondo Impacto Comunitario 2026">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>Tipo de apoyo</label>
                        <input type="text" name="convocatoria[tipo_apoyo]" class="form-control" placeholder="Subvención, grant, donación">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>Periodicidad</label>
                        <input type="text" name="convocatoria[periodicidad]" class="form-control" placeholder="Anual, abierta, única">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>Fecha de apertura</label>
                        <input type="date" name="convocatoria[fecha_apertura]" class="form-control">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>Fecha límite para aplicar</label>
                        <input type="date" name="convocatoria[fecha_cierre]" class="form-control">
                    </div>

                    <div class="col-md-2 mb-3">
                        <label>Monto mínimo</label>
                        <input type="number" step="0.01" name="convocatoria[monto_minimo]" class="form-control">
                    </div>

                    <div class="col-md-2 mb-3">
                        <label>Monto máximo</label>
                        <input type="number" step="0.01" name="convocatoria[monto_maximo]" class="form-control">
                    </div>

                    <div class="col-md-2 mb-3">
                        <label>Moneda</label>
                        <select name="convocatoria[moneda]" class="form-control">
                            <option value="USD">USD</option>
                            <option value="EUR">EUR</option>
                            <option value="GTQ">GTQ</option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Correo para alerta</label>
                        <input type="email" name="convocatoria[correo_alerta]" class="form-control" placeholder="correo@amigosproobras.org">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>Alerta 7 días antes</label>
                        <select name="convocatoria[alerta_7_dias]" class="form-control">
                            <option value="1" selected>Activada</option>
                            <option value="0">Desactivada</option>
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>Estado convocatoria</label>
                        <select name="convocatoria[estado]" class="form-control">
                            <option value="Pendiente">Pendiente</option>
                            <option value="Activa">Activa</option>
                            <option value="Cerrada">Cerrada</option>
                            <option value="Aplicada">Aplicada</option>
                            <option value="Descartada">Descartada</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Enlace de aplicación</label>
                        <input type="url" name="convocatoria[enlace]" class="form-control" placeholder="https://...">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Áreas prioritarias</label>
                        <textarea name="convocatoria[areas_prioritarias]" class="form-control" rows="2"></textarea>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Requisitos clave</label>
                        <textarea name="convocatoria[requisitos_clave]" class="form-control" rows="2"></textarea>
                    </div>

                </div>
            </div>
        </div>


        <div class="card shadow-sm mb-4">
            <div class="card-header bg-success text-white d-flex justify-content-between">
                <span>3. Contactos</span>
                <button type="button" class="btn btn-light btn-sm" onclick="agregarContacto()">+ Agregar contacto</button>
            </div>

            <div class="card-body" id="contactosContainer">
                <div class="border rounded p-3 mb-3 contacto-item">
                    <div class="row">

                        <div class="col-md-4 mb-3">
                            <label>Nombre completo</label>
                            <input type="text" name="contactos[0][nombre]" class="form-control">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Cargo</label>
                            <input type="text" name="contactos[0][cargo]" class="form-control">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Correo</label>
                            <input type="email" name="contactos[0][correo]" class="form-control">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label>Teléfono</label>
                            <input type="text" name="contactos[0][telefono]" class="form-control">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label>WhatsApp</label>
                            <input type="text" name="contactos[0][whatsapp]" class="form-control">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label>Idioma</label>
                            <input type="text" name="contactos[0][idioma]" class="form-control">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label>Medio preferido</label>
                            <input type="text" name="contactos[0][medio_preferido]" class="form-control">
                        </div>

                        <div class="col-md-12 mb-3">
                            <label>Notas</label>
                            <textarea name="contactos[0][notas]" class="form-control" rows="2"></textarea>
                        </div>

                    </div>
                </div>
            </div>
        </div>


        <div class="card shadow-sm mb-4">
            <div class="card-header bg-info text-white d-flex justify-content-between">
                <span>4. Teléfonos de la organización</span>
                <button type="button" class="btn btn-light btn-sm" onclick="agregarTelefono()">+ Agregar teléfono</button>
            </div>

            <div class="card-body" id="telefonosContainer">
                <div class="border rounded p-3 mb-3 telefono-item">
                    <div class="row">

                        <div class="col-md-3 mb-3">
                            <label>Tipo</label>
                            <input type="text" name="telefonos[0][tipo]" class="form-control" placeholder="Oficina, WhatsApp, recepción">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label>Número</label>
                            <input type="text" name="telefonos[0][numero]" class="form-control">
                        </div>

                        <div class="col-md-2 mb-3">
                            <label>Extensión</label>
                            <input type="text" name="telefonos[0][extension]" class="form-control">
                        </div>

                        <div class="col-md-2 mb-3">
                            <label>País</label>
                            <input type="text" name="telefonos[0][pais]" class="form-control">
                        </div>

                        <div class="col-md-2 mb-3">
                            <label>Observaciones</label>
                            <input type="text" name="telefonos[0][observaciones]" class="form-control">
                        </div>

                    </div>
                </div>
            </div>
        </div>


        <div class="card shadow-sm mb-4">
            <div class="card-header bg-warning d-flex justify-content-between">
                <span>5. Sitios web</span>
                <button type="button" class="btn btn-light btn-sm" onclick="agregarWeb()">+ Agregar sitio web</button>
            </div>

            <div class="card-body" id="websContainer">
                <div class="border rounded p-3 mb-3 web-item">
                    <div class="row">

                        <div class="col-md-3 mb-3">
                            <label>Tipo</label>
                            <input type="text" name="webs[0][tipo]" class="form-control" placeholder="Sitio oficial, convocatoria, portal">
                        </div>

                        <div class="col-md-5 mb-3">
                            <label>URL</label>
                            <input type="url" name="webs[0][url]" class="form-control">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label>Descripción</label>
                            <input type="text" name="webs[0][descripcion]" class="form-control">
                        </div>

                        <div class="col-md-1 mb-3">
                            <label>Activo</label>
                            <input type="checkbox" name="webs[0][activo]" class="form-check-input d-block mt-2" checked>
                        </div>

                    </div>
                </div>
            </div>
        </div>


        <div class="card shadow-sm mb-4">
            <div class="card-header bg-dark text-white d-flex justify-content-between">
                <span>6. Redes sociales</span>
                <button type="button" class="btn btn-light btn-sm" onclick="agregarRed()">+ Agregar red</button>
            </div>

            <div class="card-body" id="redesContainer">
                <div class="border rounded p-3 mb-3 red-item">
                    <div class="row">

                        <div class="col-md-3 mb-3">
                            <label>Red social</label>
                            <input type="text" name="redes[0][red_social]" class="form-control" placeholder="Facebook, LinkedIn, Instagram">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>URL</label>
                            <input type="url" name="redes[0][url]" class="form-control">
                        </div>

                        <div class="col-md-2 mb-3">
                            <label>Usuario</label>
                            <input type="text" name="redes[0][usuario]" class="form-control">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label>Notas</label>
                            <input type="text" name="redes[0][notas]" class="form-control">
                        </div>

                    </div>
                </div>
            </div>
        </div>


        <div class="text-end mb-5">
            <a href="{{ route('cooperantes.index') }}" class="btn btn-secondary">
                Cancelar
            </a>

            <button type="submit" class="btn btn-primary">
                Guardar Cooperante
            </button>
        </div>

    </form>

</div>


<script>
let contactoIndex = 1;
let telefonoIndex = 1;
let webIndex = 1;
let redIndex = 1;

function agregarContacto() {
    let html = `
    <div class="border rounded p-3 mb-3 contacto-item">
        <div class="d-flex justify-content-end">
            <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.contacto-item').remove()">Eliminar</button>
        </div>
        <div class="row">
            <div class="col-md-4 mb-3"><label>Nombre completo</label><input type="text" name="contactos[${contactoIndex}][nombre]" class="form-control"></div>
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
