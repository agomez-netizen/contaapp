@extends('layouts.app')

@section('title', 'Detalle Paciente')

@section('content')
@php
  $get = function($obj, array $keys, $default='—') {
    foreach ($keys as $k) {
      if (is_object($obj) && isset($obj->{$k}) && $obj->{$k} !== null && $obj->{$k} !== '') {
        return $obj->{$k};
      }
    }
    return $default;
  };

  $nombre        = $get($paciente, ['nombre', 'nombre_paciente']);
  $dpi           = $get($paciente, ['dpi']);
  $edad          = $get($paciente, ['edad']);
  $prioridad     = $get($paciente, ['prioridad'], 'NORMAL');
  $consulta      = $get($paciente, ['tipo_consulta', 'consulta']);
  $tipoOperacion = $get($paciente, ['tipo_operacion']);

  $carnet        = $get($paciente, ['no_carnet', 'carnet', 'numero_carnet']);
  $telefono      = $get($paciente, ['telefono', 'teléfono', 'tel']);
  $correo        = $get($paciente, ['correo_electronico', 'correo', 'email', 'e_mail']);
  $departamento  = $get($paciente, ['departamento']);
  $municipio     = $get($paciente, ['municipio']);
  $direccion     = $get($paciente, ['direccion', 'dirección']);
  $observaciones = $get($paciente, ['observaciones', 'comentarios', 'nota', 'notas', 'descripcion']);
@endphp

<div class="container py-4">

  <div class="card shadow-sm border-0">
    <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
      <h5 class="mb-0">Detalle Paciente #{{ $paciente->id_paciente }}</h5>

      <div class="d-flex gap-2">
        <a href="{{ route('pacientes.index') }}" class="btn btn-sm btn-light">← Volver</a>
        <a href="{{ route('pacientes.edit', $paciente->id_paciente) }}" class="btn btn-sm btn-light">✏️ Editar</a>
      </div>
    </div>

    <div class="card-body">
      <div class="row g-3">

        <div class="col-12">
          <h6 class="text-primary mb-2">Datos del Paciente</h6>
          <hr class="mt-0">
        </div>

        <div class="col-md-4">
          <div class="text-muted small">Nombre del paciente</div>
          <div class="fw-semibold">{{ $nombre }}</div>
        </div>

        <div class="col-md-4">
          <div class="text-muted small">DPI</div>
          <div class="fw-semibold">{{ $dpi }}</div>
        </div>

        <div class="col-md-4">
          <div class="text-muted small">Edad</div>
          <div class="fw-semibold">{{ $edad }}</div>
        </div>

        <div class="col-md-4">
          <div class="text-muted small">Prioridad</div>
          <div class="fw-semibold">
            @if($prioridad === 'PRIORITARIO')
              <span class="badge bg-danger">PRIORITARIO</span>
            @else
              <span class="badge bg-secondary">NORMAL</span>
            @endif
          </div>
        </div>

        <div class="col-md-4">
          <div class="text-muted small">Consulta</div>
          <div class="fw-semibold">{{ $consulta }}</div>
        </div>

        <div class="col-md-4">
          <div class="text-muted small">Tipo de Operación</div>
          <div class="fw-semibold">{{ $tipoOperacion }}</div>
        </div>

        <div class="col-md-4">
          <div class="text-muted small">No. Carnet</div>
          <div class="fw-semibold">{{ $carnet }}</div>
        </div>

        <div class="col-md-4">
          <div class="text-muted small">Teléfono</div>
          <div class="fw-semibold">{{ $telefono }}</div>
        </div>

        <div class="col-md-4">
          <div class="text-muted small">Correo electrónico</div>
          <div class="fw-semibold">{{ $correo }}</div>
        </div>

        <div class="col-md-4">
          <div class="text-muted small">Departamento</div>
          <div class="fw-semibold">{{ $departamento }}</div>
        </div>

        <div class="col-md-4">
          <div class="text-muted small">Municipio</div>
          <div class="fw-semibold">{{ $municipio }}</div>
        </div>

        @if($direccion !== '—')
        <div class="col-md-12">
          <div class="text-muted small">Dirección</div>
          <div class="fw-semibold">{{ $direccion }}</div>
        </div>
        @endif

        @if($observaciones !== '—')
        <div class="col-md-12">
          <div class="text-muted small">Observaciones</div>
          <div class="fw-semibold">{{ $observaciones }}</div>
        </div>
        @endif

        <div class="col-12 mt-2">
          <div class="alert alert-light border mb-0">
            <div class="text-muted small">Nota</div>
            <div class="fw-semibold">Este registro se muestra en modo lectura.</div>
          </div>
        </div>

      </div>
    </div>
  </div>

</div>
@endsection
