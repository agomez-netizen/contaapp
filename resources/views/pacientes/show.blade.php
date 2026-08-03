@extends('layouts.app')

@section('title', 'Detalle Paciente')

@section('content')
@php
  $valor = function ($campo, $predeterminado = '—') use ($paciente) {
      $dato = data_get($paciente, $campo);
      return ($dato !== null && $dato !== '') ? $dato : $predeterminado;
  };
@endphp

<div class="container py-4">

  <div class="card shadow-sm border-0">
    <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
      <h5 class="mb-0">Detalle Paciente #{{ $paciente->id_paciente }}</h5>

      <div class="d-flex gap-2">
        <a href="{{ route('pacientes.index') }}" class="btn btn-sm btn-light">← Volver</a>
        <a href="{{ route('pacientes.edit', $paciente->id_paciente) }}" class="btn btn-sm btn-light">Editar</a>
      </div>
    </div>

    <div class="card-body">
      <div class="row g-3">

        <div class="col-12">
          <h6 class="text-primary mb-2">Datos del Paciente</h6>
          <hr class="mt-0">
        </div>

        @foreach([
          'Nombre del paciente' => 'nombre',
          'DPI' => 'dpi',
          'Edad' => 'edad',
          'Sexo' => 'sexo',
          'Carnet' => 'carnet',
          'Teléfono' => 'telefono',
          'Correo' => 'correo',
          'Tipo de consulta' => 'tipo_consulta',
          'Tipo de operación' => 'tipo_operacion',
          'Departamento' => 'departamento',
          'Municipio' => 'municipio',
          'Organización' => 'empresa',
          'Nombre de la empresa' => 'nombre_empresa',
        ] as $etiqueta => $campo)
          <div class="col-md-4">
            <div class="text-muted small">{{ $etiqueta }}</div>
            <div class="fw-semibold">{{ $valor($campo) }}</div>
          </div>
        @endforeach

        <div class="col-md-4">
          <div class="text-muted small">Prioridad</div>
          <div class="fw-semibold">
            @if(($paciente->prioridad ?? 'NORMAL') === 'PRIORITARIO')
              <span class="badge bg-danger">PRIORITARIO</span>
            @else
              <span class="badge bg-secondary">NORMAL</span>
            @endif
          </div>
        </div>

        <div class="col-md-4">
          <div class="text-muted small">Estado del Paciente</div>
          <div class="fw-semibold">
            @if(($paciente->estado_paciente ?? 'EN ESPERA') === 'EN JORNADA')
              <span class="badge bg-success">EN JORNADA</span>
            @else
              <span class="badge bg-warning text-dark">EN ESPERA</span>
            @endif
          </div>
        </div>

        <div class="col-12 mt-4">
          <h6 class="text-primary mb-2">Rebaja, Trámite e Ingreso</h6>
          <hr class="mt-0">
        </div>

        <div class="col-md-4">
          <div class="text-muted small">Tipo de rebaja o trámite</div>
          <div class="fw-semibold">{{ $valor('tipo_rebaja_tramite') }}</div>
        </div>

        <div class="col-md-4">
          <div class="text-muted small">Institución del examen</div>
          <div class="fw-semibold">{{ $valor('institucion_examen') }}</div>
        </div>

        <div class="col-md-4">
          <div class="text-muted small">Lugar de ingreso</div>
          <div class="fw-semibold">{{ $valor('lugar_ingreso') }}</div>
        </div>

        <div class="col-12 mt-4">
          <h6 class="text-primary mb-2">Datos del Referente</h6>
          <hr class="mt-0">
        </div>

        @foreach([
          'Referido por' => 'referido_por',
          'Teléfono referente' => 'telefono_referente',
          'Tipo de consulta referente' => 'tipo_consulta_referente',
          'Tipo de contacto' => 'tipo_contacto',
        ] as $etiqueta => $campo)
          <div class="col-md-4">
            <div class="text-muted small">{{ $etiqueta }}</div>
            <div class="fw-semibold">{{ $valor($campo) }}</div>
          </div>
        @endforeach

        <div class="col-md-12">
          <div class="text-muted small">Descripción</div>
          <div class="fw-semibold">{{ $valor('descripcion') }}</div>
        </div>

        <div class="col-md-4">
          <div class="text-muted small">Creado</div>
          <div class="fw-semibold">{{ $valor('created_at') }}</div>
        </div>

        <div class="col-md-4">
          <div class="text-muted small">Actualizado</div>
          <div class="fw-semibold">{{ $valor('updated_at') }}</div>
        </div>

      </div>
    </div>
  </div>
</div>
@endsection
