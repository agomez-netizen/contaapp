@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3>{{ $organizacion->nombre }}</h3>
            <p class="text-muted">Detalle del cooperante registrado</p>
        </div>

        <div>
            <a href="{{ route('cooperantes.ficha.pdf', $organizacion->id) }}"
            class="btn btn-danger">
                Exportar PDF
            </a>
            <a href="{{ route('cooperantes.edit', $organizacion->id) }}" class="btn btn-warning">Editar</a>
            <a href="{{ route('cooperantes.index') }}" class="btn btn-secondary">Volver</a>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            1. Información de la organización
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3"><strong>Tipo:</strong><br>{{ $organizacion->tipo_organizacion ?? 'No registrado' }}</div>
                <div class="col-md-4 mb-3"><strong>País:</strong><br>{{ $organizacion->pais ?? 'No registrado' }}</div>
                <div class="col-md-4 mb-3"><strong>Correo general:</strong><br>{{ $organizacion->correo_general ?? 'No registrado' }}</div>

                <div class="col-md-6 mb-3"><strong>Dirección:</strong><br>{{ $organizacion->direccion ?? 'No registrada' }}</div>
                <div class="col-md-6 mb-3"><strong>Área de apoyo:</strong><br>{{ $organizacion->area_apoyo ?? 'No registrada' }}</div>

                <div class="col-md-4 mb-3"><strong>Enfoque geográfico:</strong><br>{{ $organizacion->enfoque_geografico ?? 'No registrado' }}</div>
                <div class="col-md-4 mb-3"><strong>Idioma:</strong><br>{{ $organizacion->idioma_comunicacion ?? 'No registrado' }}</div>
                <div class="col-md-4 mb-3"><strong>Monto estimado:</strong><br>{{ $organizacion->monto_estimado ?? 'No registrado' }}</div>

                <div class="col-md-4 mb-3"><strong>Prioridad:</strong><br>{{ $organizacion->prioridad ?? 'No registrada' }}</div>
                <div class="col-md-4 mb-3"><strong>Estado:</strong><br>{{ $organizacion->estado ?? 'No registrado' }}</div>

                <div class="col-md-12 mb-3">
                    <strong>Descripción:</strong><br>
                    {{ $organizacion->descripcion ?? 'No registrada' }}
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-danger text-white">
            2. Convocatorias registradas
        </div>

        <div class="card-body">
            @forelse($organizacion->convocatorias as $conv)
                <div class="border rounded p-3 mb-3">
                    <h5>{{ $conv->nombre }}</h5>

                    <div class="row">
                        <div class="col-md-3 mb-2"><strong>Tipo apoyo:</strong><br>{{ $conv->tipo_apoyo ?? 'No registrado' }}</div>

                        <div class="col-md-3 mb-2">
                            <strong>Fecha apertura:</strong><br>
                            {{ $conv->fecha_apertura ? \Carbon\Carbon::parse($conv->fecha_apertura)->format('d/m/Y') : 'Sin fecha' }}
                        </div>

                        <div class="col-md-3 mb-2">
                            <strong>Fecha límite:</strong><br>
                            {{ $conv->fecha_cierre ? \Carbon\Carbon::parse($conv->fecha_cierre)->format('d/m/Y') : 'Sin fecha' }}
                        </div>

                        <div class="col-md-3 mb-2"><strong>Estado:</strong><br>{{ $conv->estado ?? 'No registrado' }}</div>

                        <div class="col-md-3 mb-2">
                            <strong>Monto mínimo:</strong><br>
                            {{ $conv->monto_minimo ? $conv->moneda . ' ' . number_format($conv->monto_minimo, 2) : 'No registrado' }}
                        </div>

                        <div class="col-md-3 mb-2">
                            <strong>Monto máximo:</strong><br>
                            {{ $conv->monto_maximo ? $conv->moneda . ' ' . number_format($conv->monto_maximo, 2) : 'No registrado' }}
                        </div>

                        <div class="col-md-3 mb-2">
                            <strong>Alerta 7 días:</strong><br>
                            {{ $conv->alerta_7_dias ? 'Activada' : 'Desactivada' }}
                        </div>

                        <div class="col-md-3 mb-2"><strong>Correo alerta:</strong><br>{{ $conv->correo_alerta ?? 'No registrado' }}</div>

                        <div class="col-md-6 mb-2">
                            <strong>Enlace:</strong><br>
                            @if($conv->enlace)
                                <a href="{{ $conv->enlace }}" target="_blank">{{ $conv->enlace }}</a>
                            @else
                                Sin enlace
                            @endif
                        </div>

                        <div class="col-md-6 mb-2"><strong>Periodicidad:</strong><br>{{ $conv->periodicidad ?? 'No registrada' }}</div>
                        <div class="col-md-6 mb-2"><strong>Áreas prioritarias:</strong><br>{{ $conv->areas_prioritarias ?? 'No registradas' }}</div>
                        <div class="col-md-6 mb-2"><strong>Requisitos clave:</strong><br>{{ $conv->requisitos_clave ?? 'No registrados' }}</div>
                    </div>

                    <hr>

                    <h6 class="mb-3">Proyectos AAPOS vinculados a esta convocatoria</h6>

                    @forelse($conv->proyectosOrganizacion as $relacion)
                        <div class="border rounded p-3 mb-2 bg-light">
                            <div class="row">
                                <div class="col-md-3 mb-2">
                                    <strong>Proyecto:</strong><br>
                                    {{ $relacion->proyecto->nombre ?? 'No registrado' }}
                                </div>

                                <div class="col-md-2 mb-2">
                                    <strong>Categoría:</strong><br>
                                    {{ $relacion->proyecto->categoria ?? 'No registrada' }}
                                </div>

                                <div class="col-md-2 mb-2">
                                    <strong>Presupuesto:</strong><br>
                                    @if(optional($relacion->proyecto)->presupuesto_estimado)
                                        {{ $relacion->proyecto->moneda }} {{ number_format($relacion->proyecto->presupuesto_estimado, 2) }}
                                    @else
                                        No registrado
                                    @endif
                                </div>

                                <div class="col-md-2 mb-2">
                                    <strong>Compatibilidad:</strong><br>
                                    {{ $relacion->compatibilidad ?? 'En evaluación' }}
                                </div>

                                <div class="col-md-3 mb-2">
                                    <strong>Estado aplicación:</strong><br>
                                    {{ $relacion->estado_aplicacion ?? 'Identificada' }}
                                </div>

                                <div class="col-md-3 mb-2">
                                    <strong>Fecha aplicación:</strong><br>
                                    {{ $relacion->fecha_aplicacion ? \Carbon\Carbon::parse($relacion->fecha_aplicacion)->format('d/m/Y') : 'Pendiente' }}
                                </div>

                                <div class="col-md-3 mb-2">
                                    <strong>Monto solicitado:</strong><br>
                                    {{ $relacion->monto_solicitado ? number_format($relacion->monto_solicitado, 2) : 'No registrado' }}
                                </div>

                                <div class="col-md-3 mb-2">
                                    <strong>Probabilidad:</strong><br>
                                    {{ $relacion->probabilidad ?? 'En evaluación' }}
                                </div>

                                <div class="col-md-3 mb-2">
                                    <strong>Documentación lista:</strong><br>
                                    {{ $relacion->proyecto->documentacion_lista ?? 'No registrada' }}
                                </div>

                                <div class="col-md-12 mb-2">
                                    <strong>Observaciones:</strong><br>
                                    {{ $relacion->observaciones ?? 'Sin observaciones' }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted mb-0">No hay proyectos AAPOS vinculados a esta convocatoria.</p>
                    @endforelse

                </div>
            @empty
                <p class="text-muted">No hay convocatorias registradas.</p>
            @endforelse
        </div>
    </div>

    <div class="row">

        <div class="col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-warning">
                    3. Documentos requeridos
                </div>

                <div class="card-body">
                    @forelse($organizacion->documentosRequeridos as $doc)
                        <div class="border rounded p-3 mb-2">
                            <p class="mb-1"><strong>Documento:</strong> {{ $doc->documento }}</p>
                            <p class="mb-1"><strong>Estado:</strong> {{ $doc->disponible }}</p>
                            <p class="mb-1"><strong>Responsable:</strong> {{ $doc->responsable ?? 'No registrado' }}</p>
                            <p class="mb-1">
                                <strong>Fecha actualización:</strong>
                                {{ $doc->fecha_actualizacion ? \Carbon\Carbon::parse($doc->fecha_actualizacion)->format('d/m/Y') : 'Sin fecha' }}
                            </p>
                            <p class="mb-0"><strong>Observaciones:</strong> {{ $doc->observaciones ?? 'Sin observaciones' }}</p>
                        </div>
                    @empty
                        <p class="text-muted">No hay documentos requeridos registrados.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    4. Seguimientos
                </div>

                <div class="card-body">
                    @forelse($organizacion->seguimientos as $seg)
                        <div class="border rounded p-3 mb-2">
                            <p class="mb-1">
                                <strong>Fecha:</strong>
                                {{ $seg->fecha ? \Carbon\Carbon::parse($seg->fecha)->format('d/m/Y') : 'Sin fecha' }}
                            </p>
                            <p class="mb-1"><strong>Tipo contacto:</strong> {{ $seg->tipo_contacto ?? 'No registrado' }}</p>
                            <p class="mb-1"><strong>Responsable:</strong> {{ $seg->responsable ?? 'No registrado' }}</p>
                            <p class="mb-1"><strong>Resultado:</strong> {{ $seg->resultado ?? 'No registrado' }}</p>
                            <p class="mb-1">
                                <strong>Próximo seguimiento:</strong>
                                {{ $seg->proximo_seguimiento ? \Carbon\Carbon::parse($seg->proximo_seguimiento)->format('d/m/Y') : 'Sin fecha' }}
                            </p>
                            <p class="mb-0"><strong>Descripción:</strong> {{ $seg->descripcion ?? 'Sin descripción' }}</p>
                        </div>
                    @empty
                        <p class="text-muted">No hay seguimientos registrados.</p>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

    <div class="row">

        <div class="col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">5. Contactos</div>
                <div class="card-body">
                    @forelse($organizacion->contactos as $contacto)
                        <div class="border rounded p-3 mb-3">
                            <h6>{{ $contacto->nombre }}</h6>
                            <p class="mb-1"><strong>Cargo:</strong> {{ $contacto->cargo ?? 'No registrado' }}</p>
                            <p class="mb-1"><strong>Correo:</strong> {{ $contacto->correo ?? 'No registrado' }}</p>
                            <p class="mb-1"><strong>Teléfono:</strong> {{ $contacto->telefono ?? 'No registrado' }}</p>
                            <p class="mb-1"><strong>WhatsApp:</strong> {{ $contacto->whatsapp ?? 'No registrado' }}</p>
                            <p class="mb-1"><strong>Idioma:</strong> {{ $contacto->idioma ?? 'No registrado' }}</p>
                            <p class="mb-1"><strong>Medio preferido:</strong> {{ $contacto->medio_preferido ?? 'No registrado' }}</p>
                            <p class="mb-0"><strong>Notas:</strong> {{ $contacto->notas ?? 'Sin notas' }}</p>
                        </div>
                    @empty
                        <p class="text-muted">No hay contactos registrados.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">6. Teléfonos</div>
                <div class="card-body">
                    @forelse($organizacion->telefonos as $tel)
                        <div class="border rounded p-3 mb-3">
                            <p class="mb-1"><strong>Tipo:</strong> {{ $tel->tipo ?? 'No registrado' }}</p>
                            <p class="mb-1"><strong>Número:</strong> {{ $tel->numero }}</p>
                            <p class="mb-1"><strong>Extensión:</strong> {{ $tel->extension ?? 'No registrada' }}</p>
                            <p class="mb-1"><strong>País:</strong> {{ $tel->pais ?? 'No registrado' }}</p>
                            <p class="mb-0"><strong>Observaciones:</strong> {{ $tel->observaciones ?? 'Sin observaciones' }}</p>
                        </div>
                    @empty
                        <p class="text-muted">No hay teléfonos registrados.</p>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

    <div class="row">

        <div class="col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-warning">7. Sitios web</div>
                <div class="card-body">
                    @forelse($organizacion->webs as $web)
                        <div class="border rounded p-3 mb-3">
                            <p class="mb-1"><strong>Tipo:</strong> {{ $web->tipo ?? 'Sitio web' }}</p>
                            <p class="mb-1">
                                <strong>URL:</strong>
                                <a href="{{ $web->url }}" target="_blank">{{ $web->url }}</a>
                            </p>
                            <p class="mb-1"><strong>Descripción:</strong> {{ $web->descripcion ?? 'Sin descripción' }}</p>
                            <p class="mb-0"><strong>Activo:</strong> {{ $web->activo ? 'Sí' : 'No' }}</p>
                        </div>
                    @empty
                        <p class="text-muted">No hay sitios web registrados.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-dark text-white">8. Redes sociales</div>
                <div class="card-body">
                    @forelse($organizacion->redes as $red)
                        <div class="border rounded p-3 mb-3">
                            <p class="mb-1"><strong>Red:</strong> {{ $red->red_social ?? 'No registrada' }}</p>
                            <p class="mb-1"><strong>Usuario:</strong> {{ $red->usuario ?? 'No registrado' }}</p>
                            <p class="mb-1">
                                <strong>URL:</strong>
                                @if($red->url)
                                    <a href="{{ $red->url }}" target="_blank">{{ $red->url }}</a>
                                @else
                                    No registrada
                                @endif
                            </p>
                            <p class="mb-0"><strong>Notas:</strong> {{ $red->notas ?? 'Sin notas' }}</p>
                        </div>
                    @empty
                        <p class="text-muted">No hay redes sociales registradas.</p>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

</div>

@endsection
