@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3>{{ $organizacion->nombre }}</h3>
            <p class="text-muted">Detalle del cooperante registrado</p>
        </div>

        <div>
            <a href="{{ route('cooperantes.edit', $organizacion->id) }}" class="btn btn-warning">
                Editar
            </a>

            <a href="{{ route('cooperantes.index') }}" class="btn btn-secondary">
                Volver
            </a>
        </div>
    </div>


    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            Información de la organización
        </div>

        <div class="card-body">
            <div class="row">

                <div class="col-md-4 mb-3">
                    <strong>Tipo:</strong><br>
                    {{ $organizacion->tipo_organizacion }}
                </div>

                <div class="col-md-4 mb-3">
                    <strong>País:</strong><br>
                    {{ $organizacion->pais }}
                </div>

                <div class="col-md-4 mb-3">
                    <strong>Correo general:</strong><br>
                    {{ $organizacion->correo_general }}
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Dirección:</strong><br>
                    {{ $organizacion->direccion }}
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Área de apoyo:</strong><br>
                    {{ $organizacion->area_apoyo }}
                </div>

                <div class="col-md-4 mb-3">
                    <strong>Enfoque geográfico:</strong><br>
                    {{ $organizacion->enfoque_geografico }}
                </div>

                <div class="col-md-4 mb-3">
                    <strong>Idioma:</strong><br>
                    {{ $organizacion->idioma_comunicacion }}
                </div>

                <div class="col-md-4 mb-3">
                    <strong>Monto estimado:</strong><br>
                    {{ $organizacion->monto_estimado }}
                </div>

                <div class="col-md-4 mb-3">
                    <strong>Prioridad:</strong><br>
                    {{ $organizacion->prioridad }}
                </div>

                <div class="col-md-4 mb-3">
                    <strong>Estado:</strong><br>
                    {{ $organizacion->estado }}
                </div>

                <div class="col-md-12 mb-3">
                    <strong>Descripción:</strong><br>
                    {{ $organizacion->descripcion }}
                </div>

            </div>
        </div>
    </div>


    <div class="row">

        <div class="col-md-6">

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    Contactos
                </div>

                <div class="card-body">

                    @forelse($organizacion->contactos as $contacto)

                        <div class="border rounded p-3 mb-3">
                            <h6>{{ $contacto->nombre }}</h6>

                            <p class="mb-1">
                                <strong>Cargo:</strong> {{ $contacto->cargo }}
                            </p>

                            <p class="mb-1">
                                <strong>Correo:</strong> {{ $contacto->correo }}
                            </p>

                            <p class="mb-1">
                                <strong>Teléfono:</strong> {{ $contacto->telefono }}
                            </p>

                            <p class="mb-1">
                                <strong>WhatsApp:</strong> {{ $contacto->whatsapp }}
                            </p>

                            <p class="mb-1">
                                <strong>Idioma:</strong> {{ $contacto->idioma }}
                            </p>

                            <p class="mb-1">
                                <strong>Medio preferido:</strong> {{ $contacto->medio_preferido }}
                            </p>

                            <p class="mb-0">
                                <strong>Notas:</strong> {{ $contacto->notas }}
                            </p>
                        </div>

                    @empty

                        <p class="text-muted">No hay contactos registrados.</p>

                    @endforelse

                </div>
            </div>

        </div>


        <div class="col-md-6">

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    Teléfonos
                </div>

                <div class="card-body">

                    @forelse($organizacion->telefonos as $tel)

                        <div class="border rounded p-3 mb-3">
                            <p class="mb-1">
                                <strong>Tipo:</strong> {{ $tel->tipo }}
                            </p>

                            <p class="mb-1">
                                <strong>Número:</strong> {{ $tel->numero }}
                            </p>

                            <p class="mb-1">
                                <strong>Extensión:</strong> {{ $tel->extension }}
                            </p>

                            <p class="mb-1">
                                <strong>País:</strong> {{ $tel->pais }}
                            </p>

                            <p class="mb-0">
                                <strong>Observaciones:</strong> {{ $tel->observaciones }}
                            </p>
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
                <div class="card-header bg-warning">
                    Sitios web
                </div>

                <div class="card-body">

                    @forelse($organizacion->webs as $web)

                        <div class="border rounded p-3 mb-3">
                            <p class="mb-1">
                                <strong>Tipo:</strong> {{ $web->tipo }}
                            </p>

                            <p class="mb-1">
                                <strong>URL:</strong>
                                <a href="{{ $web->url }}" target="_blank">
                                    {{ $web->url }}
                                </a>
                            </p>

                            <p class="mb-1">
                                <strong>Descripción:</strong> {{ $web->descripcion }}
                            </p>

                            <p class="mb-0">
                                <strong>Activo:</strong> {{ $web->activo ? 'Sí' : 'No' }}
                            </p>
                        </div>

                    @empty

                        <p class="text-muted">No hay sitios web registrados.</p>

                    @endforelse

                </div>
            </div>

        </div>


        <div class="col-md-6">

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-dark text-white">
                    Redes sociales
                </div>

                <div class="card-body">

                    @forelse($organizacion->redes as $red)

                        <div class="border rounded p-3 mb-3">
                            <p class="mb-1">
                                <strong>Red:</strong> {{ $red->red_social }}
                            </p>

                            <p class="mb-1">
                                <strong>Usuario:</strong> {{ $red->usuario }}
                            </p>

                            <p class="mb-1">
                                <strong>URL:</strong>
                                <a href="{{ $red->url }}" target="_blank">
                                    {{ $red->url }}
                                </a>
                            </p>

                            <p class="mb-0">
                                <strong>Notas:</strong> {{ $red->notas }}
                            </p>
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
