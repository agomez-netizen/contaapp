@extends('layouts.app')

@section('title', 'Detalle Paciente')

@section('content')
@php
  // Getter robusto para Eloquent/arrays
  $get = function($obj, array $keys, $default = '—') {
    foreach ($keys as $k) {
      $val = data_get($obj, $k);
      if ($val !== null && $val !== '') return $val;
    }
    return $default;
  };

  // Campos según tu tabla
  $idPaciente          = $get($paciente, ['id_paciente']);
  $nombre             = $get($paciente, ['nombre', 'nombre_paciente']);
  $dpi                = $get($paciente, ['dpi']);
  $edad               = $get($paciente, ['edad']);
  $sexo               = $get($paciente, ['sexo']);
  $prioridad          = $get($paciente, ['prioridad'], 'NORMAL');

  $tipoConsulta        = $get($paciente, ['tipo_consulta', 'consulta']);
  $tipoOperacion       = $get($paciente, ['tipo_operacion']);

  $carnet              = $get($paciente, ['carnet', 'no_carnet', 'numero_carnet']);
  $telefono            = $get($paciente, ['telefono', 'teléfono', 'tel']);
  $correo              = $get($paciente, ['correo', 'correo_electronico', 'email', 'e_mail']);

  $empresa             = $get($paciente, ['empresa']);
  $nombreEmpresa       = $get($paciente, ['nombre_empresa']);

  $departamento        = $get($paciente, ['departamento']);
  $municipio           = $get($paciente, ['municipio']);

  $referidoPor         = $get($paciente, ['referido_por']);
  $telefonoReferente   = $get($paciente, ['telefono_referente']);
  $tipoConsultaRef     = $get($paciente, ['tipo_consulta_referente']);
  $tipoContacto        = $get($paciente, ['tipo_contacto']);

  $descripcion         = $get($paciente, ['descripcion']);

  $createdAt           = $get($paciente, ['created_at']);
  $updatedAt           = $get($paciente, ['updated_at']);

  // Helper rápido para imprimir tarjetas de campos
  $field = function($label, $value) {
    return '
      <div class="col-md-4">
        <div class="text-muted small">'.e($label).'</div>
        <div class="fw-semibold">'.e($value).'</div>
      </div>
    ';
  };
@endphp

<div class="container py-4">

  <div class="card shadow-sm border-0">
    <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
      <h5 class="mb-0">Detalle Paciente #{{ $idPaciente }}</h5>

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

        {!! $field('Nombre del paciente', $nombre) !!}
        {!! $field('DPI', $dpi) !!}
        {!! $field('Edad', $edad) !!}
        {!! $field('Sexo', $sexo) !!}

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

        {!! $field('Tipo de consulta', $tipoConsulta) !!}
        {!! $field('Tipo de operación', $tipoOperacion) !!}
        {!! $field('Carnet', $carnet) !!}
        {!! $field('Teléfono', $telefono) !!}
        {!! $field('Correo', $correo) !!}

        {!! $field('Empresa', $empresa) !!}
        {!! $field('Nombre empresa', $nombreEmpresa) !!}

        {!! $field('Departamento', $departamento) !!}
        {!! $field('Municipio', $municipio) !!}

        {!! $field('Referido por', $referidoPor) !!}
        {!! $field('Teléfono referente', $telefonoReferente) !!}
        {!! $field('Tipo consulta referente', $tipoConsultaRef) !!}
        {!! $field('Tipo de contacto', $tipoContacto) !!}

        <div class="col-md-12">
          <div class="text-muted small">Descripción</div>
          <div class="fw-semibold">{{ $descripcion }}</div>
        </div>

        {!! $field('Creado', $createdAt) !!}
        {!! $field('Actualizado', $updatedAt) !!}

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
