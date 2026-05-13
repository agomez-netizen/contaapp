@extends('layouts.app')

@section('content')

<div class="container-fluid py-3">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-0">Control Financiero de Proyecto</h3>
            <small class="text-muted">Registro y control de movimientos financieros</small>
        </div>

        <a href="{{ route('finanzas.historial') }}" class="btn btn-outline-primary">
            Ver Historial
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white">
            <h5 class="mb-0">Nuevo Movimiento</h5>
        </div>

        <div class="card-body">
            <form action="{{ route('finanzas.store') }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf

                @include('finanzas.form')

            </form>
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const proyecto = document.getElementById('id_proyecto');
    const subproyecto = document.getElementById('id_subproyecto');

    if (!proyecto || !subproyecto) {
        return;
    }

    proyecto.addEventListener('change', function () {

        const proyectoId = this.value;

        subproyecto.innerHTML = '<option value="">Cargando...</option>';

        if (!proyectoId) {
            subproyecto.innerHTML = '<option value="">Seleccione proyecto primero</option>';
            return;
        }

        fetch("{{ url('/subproyectos/proyecto') }}/" + proyectoId)
            .then(response => response.json())
            .then(data => {

                subproyecto.innerHTML = '<option value="">Seleccione</option>';

                if (data.length === 0) {
                    subproyecto.innerHTML = '<option value="">Sin subproyectos</option>';
                    return;
                }

                data.forEach(item => {
                    subproyecto.innerHTML += `
                        <option value="${item.id_subproyecto}">
                            ${item.nombre}
                        </option>
                    `;
                });

            })
            .catch(error => {
                console.error(error);
                subproyecto.innerHTML = '<option value="">Error al cargar</option>';
            });

    });

});
</script>

@endsection
