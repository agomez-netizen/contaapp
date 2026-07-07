@extends('layouts.app')

@section('content')

<div class="container-fluid">


    <!-- ENCABEZADO -->
    <div class="card shadow-sm border-0 mb-4">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h3 class="mb-1">
                        Directorio de Cooperantes
                    </h3>

                    <span class="text-muted">
                        Organizaciones, convocatorias y subvenciones internacionales
                    </span>

                </div>


                <div class="d-flex gap-2">


                    <a href="{{ route('cooperantes.exportar') }}"
                       class="btn btn-success shadow-sm">

                        <i class="bi bi-file-earmark-excel"></i>
                        Exportar Excel

                    </a>


                    <a href="{{ route('cooperantes.create') }}"
                       class="btn btn-primary shadow-sm">

                        <i class="bi bi-plus-circle"></i>
                        Nuevo Cooperante

                    </a>


                </div>


            </div>

        </div>

    </div>



    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif



    <!-- INDICADORES -->
    <div class="row mb-4">


        <div class="col-md-3">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <small class="text-muted">
                        Total cooperantes
                    </small>

                    <h2 class="mb-0">

                        {{ $organizaciones->total() }}

                    </h2>


                </div>

            </div>

        </div>


    </div>





    <!-- TABLA -->
    <div class="card border-0 shadow-sm">


        <div class="card-header bg-white">

            <strong>
                Cooperantes registrados
            </strong>

        </div>



        <div class="card-body">


            <div class="table-responsive">


                <table class="table table-hover align-middle">


                    <thead>

                        <tr>

                            <th>Organización</th>

                            <th>Tipo</th>

                            <th>País</th>

                            <th>Área apoyo</th>

                            <th class="text-center">
                                Prioridad
                            </th>

                            <th class="text-center">
                                Estado
                            </th>

                            <th class="text-center" width="140">
                                Acciones
                            </th>

                        </tr>

                    </thead>



                    <tbody>


                    @forelse($organizaciones as $org)


                    <tr>


                        <td>

                            <strong>
                                {{ $org->nombre }}
                            </strong>

                            <br>

                            <small class="text-muted">

                                {{ $org->correo_general }}

                            </small>

                        </td>



                        <td>

                            {{ $org->tipo_organizacion }}

                        </td>



                        <td>

                            {{ $org->pais }}

                        </td>



                        <td>

                            {{ $org->area_apoyo }}

                        </td>




                        <td class="text-center">


                            @if($org->prioridad=="Alta")

                                <span class="badge rounded-pill bg-danger">

                                    Alta

                                </span>


                            @elseif($org->prioridad=="Media")


                                <span class="badge rounded-pill bg-warning text-dark">

                                    Media

                                </span>


                            @else


                                <span class="badge rounded-pill bg-secondary">

                                    Baja

                                </span>


                            @endif



                        </td>





                        <td class="text-center">


                            <span class="badge rounded-pill bg-info">

                                {{ $org->estado }}

                            </span>


                        </td>





                        <td>


                            <div class="d-flex justify-content-center gap-1">


                                <a href="{{ route('cooperantes.show',$org->id) }}"
                                   class="btn btn-sm btn-outline-info"
                                   title="Ver detalle">

                                    <i class="bi bi-eye"></i>

                                </a>




                                <a href="{{ route('cooperantes.edit',$org->id) }}"
                                   class="btn btn-sm btn-outline-warning"
                                   title="Editar">

                                    <i class="bi bi-pencil"></i>

                                </a>




                                <form
                                    action="{{ route('cooperantes.destroy',$org->id) }}"
                                    method="POST">

                                    @csrf

                                    @method('DELETE')


                                    <button
                                        onclick="return confirm('¿Eliminar cooperante?')"
                                        class="btn btn-sm btn-outline-danger"
                                        title="Eliminar">


                                        <i class="bi bi-trash"></i>


                                    </button>


                                </form>



                            </div>


                        </td>


                    </tr>



                    @empty



                    <tr>

                        <td colspan="7" class="text-center text-muted py-4">

                            No existen cooperantes registrados

                        </td>

                    </tr>




                    @endforelse


                    </tbody>



                </table>


            </div>




            <div class="mt-3">

                {{ $organizaciones->links() }}

            </div>




        </div>


    </div>


</div>


@endsection
