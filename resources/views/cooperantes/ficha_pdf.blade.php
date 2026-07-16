<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ficha técnica - {{ $organizacion->nombre }}</title>

    <style>
        @page {
            margin: 26px 30px 34px 30px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
            color: #1f2937;
            line-height: 1.35;
        }

        h1, h2, h3, p {
            margin: 0;
        }

        .encabezado {
            border-bottom: 3px solid #0b2f5b;
            padding-bottom: 10px;
            margin-bottom: 14px;
        }

        .titulo {
            color: #0b2f5b;
            font-size: 20px;
            font-weight: bold;
        }

        .subtitulo {
            margin-top: 4px;
            color: #6b7280;
            font-size: 10px;
        }

        .fecha {
            text-align: right;
            color: #6b7280;
            font-size: 8px;
            margin-top: 4px;
        }

        .seccion {
            margin-bottom: 12px;
            page-break-inside: avoid;
        }

        .seccion-titulo {
            padding: 7px 9px;
            color: #ffffff;
            font-size: 11px;
            font-weight: bold;
        }

        .azul { background: #1677f2; }
        .rojo { background: #dc3545; }
        .verde { background: #198754; }
        .naranja { background: #f59e0b; }
        .morado { background: #6f42c1; }
        .gris { background: #343a40; }

        .contenido {
            border: 1px solid #d7dde5;
            border-top: 0;
            padding: 9px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td, th {
            vertical-align: top;
            padding: 5px 6px;
        }

        th {
            background: #eef2f7;
            border: 1px solid #d7dde5;
            text-align: left;
            font-size: 8px;
        }

        .tabla-datos td {
            width: 33.33%;
            border-bottom: 1px solid #edf0f4;
        }

        .etiqueta {
            display: block;
            font-weight: bold;
            color: #111827;
            margin-bottom: 2px;
        }

        .tabla-listado td {
            border: 1px solid #d7dde5;
        }

        .texto-largo {
            white-space: pre-line;
        }

        .badge {
            display: inline-block;
            padding: 2px 6px;
            border: 1px solid #b8c2cc;
            border-radius: 3px;
            background: #f8fafc;
        }

        .pie {
            position: fixed;
            bottom: -22px;
            left: 0;
            right: 0;
            border-top: 1px solid #d7dde5;
            padding-top: 5px;
            color: #6b7280;
            font-size: 7px;
            text-align: center;
        }

        .sin-registros {
            color: #6b7280;
            font-style: italic;
        }

        a {
            color: #0d6efd;
            text-decoration: none;
        }
    </style>
</head>

<body>

<div class="pie">
    Asociación Amigos Pro Obras Sociales - Ficha técnica de cooperación internacional
</div>

<div class="encabezado">
    <div class="titulo">{{ $organizacion->nombre }}</div>
    <div class="subtitulo">Ficha técnica del cooperante</div>
    <div class="fecha">
        Generada el {{ now()->format('d/m/Y H:i') }}
    </div>
</div>

<div class="seccion">
    <div class="seccion-titulo azul">1. Información de la organización</div>

    <div class="contenido">
        <table class="tabla-datos">
            <tr>
                <td>
                    <span class="etiqueta">Tipo</span>
                    {{ $organizacion->tipo_organizacion ?: 'No registrado' }}
                </td>
                <td>
                    <span class="etiqueta">País</span>
                    {{ $organizacion->pais ?: 'No registrado' }}
                </td>
                <td>
                    <span class="etiqueta">Correo general</span>
                    {{ $organizacion->correo_general ?: 'No registrado' }}
                </td>
            </tr>

            <tr>
                <td colspan="2">
                    <span class="etiqueta">Dirección</span>
                    {{ $organizacion->direccion ?: 'No registrada' }}
                </td>
                <td>
                    <span class="etiqueta">Área de apoyo</span>
                    {{ $organizacion->area_apoyo ?: 'No registrada' }}
                </td>
            </tr>

            <tr>
                <td>
                    <span class="etiqueta">Enfoque geográfico</span>
                    {{ $organizacion->enfoque_geografico ?: 'No registrado' }}
                </td>
                <td>
                    <span class="etiqueta">Idioma</span>
                    {{ $organizacion->idioma_comunicacion ?: 'No registrado' }}
                </td>
                <td>
                    <span class="etiqueta">Monto estimado</span>
                    {{ $organizacion->monto_estimado ?: 'No registrado' }}
                </td>
            </tr>

            <tr>
                <td>
                    <span class="etiqueta">Prioridad</span>
                    {{ $organizacion->prioridad ?: 'No registrada' }}
                </td>
                <td>
                    <span class="etiqueta">Estado</span>
                    {{ $organizacion->estado ?: 'No registrado' }}
                </td>
                <td></td>
            </tr>

            <tr>
                <td colspan="3">
                    <span class="etiqueta">Descripción</span>
                    <div class="texto-largo">
                        {{ $organizacion->descripcion ?: 'No registrada' }}
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>

<div class="seccion">
    <div class="seccion-titulo rojo">2. Convocatorias registradas</div>

    <div class="contenido">
        @forelse($organizacion->convocatorias as $conv)
            <table class="tabla-listado" style="margin-bottom: 10px;">
                <tr>
                    <th colspan="4" style="font-size: 10px;">
                        {{ $conv->nombre }}
                    </th>
                </tr>

                <tr>
                    <td>
                        <span class="etiqueta">Tipo de apoyo</span>
                        {{ $conv->tipo_apoyo ?: 'No registrado' }}
                    </td>
                    <td>
                        <span class="etiqueta">Apertura</span>
                        {{ $conv->fecha_apertura ? \Carbon\Carbon::parse($conv->fecha_apertura)->format('d/m/Y') : 'Sin fecha' }}
                    </td>
                    <td>
                        <span class="etiqueta">Cierre</span>
                        {{ $conv->fecha_cierre ? \Carbon\Carbon::parse($conv->fecha_cierre)->format('d/m/Y') : 'Sin fecha' }}
                    </td>
                    <td>
                        <span class="etiqueta">Estado</span>
                        {{ $conv->estado ?: 'No registrado' }}
                    </td>
                </tr>

                <tr>
                    <td>
                        <span class="etiqueta">Monto mínimo</span>
                        {{ $conv->monto_minimo !== null ? $conv->moneda . ' ' . number_format($conv->monto_minimo, 2) : 'No registrado' }}
                    </td>
                    <td>
                        <span class="etiqueta">Monto máximo</span>
                        {{ $conv->monto_maximo !== null ? $conv->moneda . ' ' . number_format($conv->monto_maximo, 2) : 'No registrado' }}
                    </td>
                    <td>
                        <span class="etiqueta">Periodicidad</span>
                        {{ $conv->periodicidad ?: 'No registrada' }}
                    </td>
                    <td>
                        <span class="etiqueta">Alerta</span>
                        {{ $conv->alerta_7_dias ? 'Activada' : 'Desactivada' }}
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <span class="etiqueta">Áreas prioritarias</span>
                        {{ $conv->areas_prioritarias ?: 'No registradas' }}
                    </td>
                    <td colspan="2">
                        <span class="etiqueta">Requisitos clave</span>
                        {{ $conv->requisitos_clave ?: 'No registrados' }}
                    </td>
                </tr>

                <tr>
                    <td colspan="4">
                        <span class="etiqueta">Enlace</span>
                        {{ $conv->enlace ?: 'Sin enlace' }}
                    </td>
                </tr>
            </table>

            @if($conv->proyectosOrganizacion->isNotEmpty())
                <table class="tabla-listado" style="margin-bottom: 12px;">
                    <tr>
                        <th>Proyecto AAPOS</th>
                        <th>Categoría</th>
                        <th>Compatibilidad</th>
                        <th>Estado de aplicación</th>
                        <th>Monto solicitado</th>
                    </tr>

                    @foreach($conv->proyectosOrganizacion as $relacion)
                        <tr>
                            <td>{{ optional($relacion->proyecto)->nombre ?: 'No registrado' }}</td>
                            <td>{{ optional($relacion->proyecto)->categoria ?: 'No registrada' }}</td>
                            <td>{{ $relacion->compatibilidad ?: 'En evaluación' }}</td>
                            <td>{{ $relacion->estado_aplicacion ?: 'Identificada' }}</td>
                            <td>
                                {{ $relacion->monto_solicitado !== null
                                    ? number_format($relacion->monto_solicitado, 2)
                                    : 'No registrado' }}
                            </td>
                        </tr>
                    @endforeach
                </table>
            @endif
        @empty
            <div class="sin-registros">No hay convocatorias registradas.</div>
        @endforelse
    </div>
</div>

<div class="seccion">
    <div class="seccion-titulo naranja">3. Documentos requeridos</div>

    <div class="contenido">
        @if($organizacion->documentosRequeridos->isEmpty())
            <div class="sin-registros">No hay documentos requeridos registrados.</div>
        @else
            <table class="tabla-listado">
                <tr>
                    <th>Documento</th>
                    <th>Disponibilidad</th>
                    <th>Responsable</th>
                    <th>Actualización</th>
                    <th>Observaciones</th>
                </tr>

                @foreach($organizacion->documentosRequeridos as $doc)
                    <tr>
                        <td>{{ $doc->documento }}</td>
                        <td>{{ $doc->disponible }}</td>
                        <td>{{ $doc->responsable ?: 'No registrado' }}</td>
                        <td>
                            {{ $doc->fecha_actualizacion
                                ? \Carbon\Carbon::parse($doc->fecha_actualizacion)->format('d/m/Y')
                                : 'Sin fecha' }}
                        </td>
                        <td>{{ $doc->observaciones ?: 'Sin observaciones' }}</td>
                    </tr>
                @endforeach
            </table>
        @endif
    </div>
</div>

<div class="seccion">
    <div class="seccion-titulo morado">4. Seguimientos</div>

    <div class="contenido">
        @if($organizacion->seguimientos->isEmpty())
            <div class="sin-registros">No hay seguimientos registrados.</div>
        @else
            <table class="tabla-listado">
                <tr>
                    <th>Fecha</th>
                    <th>Tipo de contacto</th>
                    <th>Responsable</th>
                    <th>Resultado</th>
                    <th>Próximo seguimiento</th>
                    <th>Descripción</th>
                </tr>

                @foreach($organizacion->seguimientos as $seg)
                    <tr>
                        <td>
                            {{ $seg->fecha
                                ? \Carbon\Carbon::parse($seg->fecha)->format('d/m/Y')
                                : 'Sin fecha' }}
                        </td>
                        <td>{{ $seg->tipo_contacto ?: 'No registrado' }}</td>
                        <td>{{ $seg->responsable ?: 'No registrado' }}</td>
                        <td>{{ $seg->resultado ?: 'No registrado' }}</td>
                        <td>
                            {{ $seg->proximo_seguimiento
                                ? \Carbon\Carbon::parse($seg->proximo_seguimiento)->format('d/m/Y')
                                : 'Sin fecha' }}
                        </td>
                        <td>{{ $seg->descripcion ?: 'Sin descripción' }}</td>
                    </tr>
                @endforeach
            </table>
        @endif
    </div>
</div>

<div class="seccion">
    <div class="seccion-titulo verde">5. Contactos</div>

    <div class="contenido">
        @if($organizacion->contactos->isEmpty())
            <div class="sin-registros">No hay contactos registrados.</div>
        @else
            <table class="tabla-listado">
                <tr>
                    <th>Nombre</th>
                    <th>Cargo</th>
                    <th>Correo</th>
                    <th>Teléfono</th>
                    <th>WhatsApp</th>
                    <th>Medio preferido</th>
                </tr>

                @foreach($organizacion->contactos as $contacto)
                    <tr>
                        <td>{{ $contacto->nombre }}</td>
                        <td>{{ $contacto->cargo ?: 'No registrado' }}</td>
                        <td>{{ $contacto->correo ?: 'No registrado' }}</td>
                        <td>{{ $contacto->telefono ?: 'No registrado' }}</td>
                        <td>{{ $contacto->whatsapp ?: 'No registrado' }}</td>
                        <td>{{ $contacto->medio_preferido ?: 'No registrado' }}</td>
                    </tr>
                @endforeach
            </table>
        @endif
    </div>
</div>

<div class="seccion">
    <div class="seccion-titulo gris">6. Canales digitales</div>

    <div class="contenido">
        <table class="tabla-listado">
            <tr>
                <th>Tipo</th>
                <th>Detalle</th>
                <th>Observaciones</th>
            </tr>

            @foreach($organizacion->telefonos as $tel)
                <tr>
                    <td>Teléfono - {{ $tel->tipo ?: 'General' }}</td>
                    <td>
                        {{ $tel->numero }}
                        {{ $tel->extension ? ' Ext. ' . $tel->extension : '' }}
                    </td>
                    <td>{{ $tel->observaciones ?: 'Sin observaciones' }}</td>
                </tr>
            @endforeach

            @foreach($organizacion->webs as $web)
                <tr>
                    <td>Sitio web - {{ $web->tipo ?: 'General' }}</td>
                    <td>{{ $web->url }}</td>
                    <td>{{ $web->descripcion ?: 'Sin descripción' }}</td>
                </tr>
            @endforeach

            @foreach($organizacion->redes as $red)
                <tr>
                    <td>Red social - {{ $red->red_social ?: 'General' }}</td>
                    <td>{{ $red->url ?: $red->usuario }}</td>
                    <td>{{ $red->notas ?: 'Sin observaciones' }}</td>
                </tr>
            @endforeach

            @if(
                $organizacion->telefonos->isEmpty()
                && $organizacion->webs->isEmpty()
                && $organizacion->redes->isEmpty()
            )
                <tr>
                    <td colspan="3" class="sin-registros">
                        No hay canales digitales registrados.
                    </td>
                </tr>
            @endif
        </table>
    </div>
</div>

</body>
</html>
