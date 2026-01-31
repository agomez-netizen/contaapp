@extends('layouts.app')

@section('title', 'Editar Paciente')

@section('content')
<div class="container py-4">

  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h3 class="fw-bold mb-1">✏️ Editar Paciente</h3>
      <div class="text-muted">Actualiza la información del paciente</div>
    </div>

    <a href="{{ route('pacientes.index') }}" class="btn btn-outline-secondary">
      ← Volver
    </a>
  </div>

  @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <strong>Corrige los siguientes errores:</strong>
      <ul class="mb-0">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
  @endif

  <div class="card border-0 shadow-sm">
    <div class="card-body">

      {{-- FORM PRINCIPAL: UPDATE --}}
      <form method="POST" action="{{ route('pacientes.update', $paciente->id_paciente) }}">
        @csrf
        @method('PUT')

        <h6 class="fw-semibold mb-3">📋 Datos del Paciente</h6>

        <div class="row g-3 mb-4">
          <div class="col-md-6">
            <label class="form-label">Nombre del Paciente</label>
            <input type="text" name="nombre" class="form-control" required
                   value="{{ old('nombre', $paciente->nombre) }}">
          </div>

          <div class="col-md-6">
            <label class="form-label">DPI</label>
            <input type="text" name="dpi" class="form-control" required
                   value="{{ old('dpi', $paciente->dpi) }}">
          </div>

          <div class="col-md-4">
            <label class="form-label">Sexo</label>
            <select name="sexo" class="form-select" required>
              <option value="">Seleccione</option>
              <option value="MASCULINO" {{ old('sexo', $paciente->sexo)=='MASCULINO'?'selected':'' }}>MASCULINO</option>
              <option value="FEMENINO"  {{ old('sexo', $paciente->sexo)=='FEMENINO'?'selected':'' }}>FEMENINO</option>
            </select>
          </div>

          <div class="col-md-4">
            <label class="form-label">Edad</label>
            <input type="number" name="edad" class="form-control" min="0" required
                   value="{{ old('edad', $paciente->edad) }}">
          </div>

          <div class="col-md-4">
            <label class="form-label">No. Carnet</label>
            <input type="text" name="carnet" class="form-control"
                   value="{{ old('carnet', $paciente->carnet) }}">
          </div>

          <div class="col-md-4">
            <label class="form-label">Teléfono</label>
            <input type="text" name="telefono" class="form-control"
                   value="{{ old('telefono', $paciente->telefono) }}">
          </div>

          <div class="col-md-4">
            <label class="form-label">Correo Electrónico</label>
            <input type="email" name="correo" class="form-control"
                   value="{{ old('correo', $paciente->correo) }}">
          </div>

          <div class="col-md-4">
            <label class="form-label">Departamento</label>
            @php
              $deps = ['Guatemala','Escuintla','Sacatepéquez','Chimaltenango','Quetzaltenango','Alta Verapaz','Baja Verapaz','Petén','Izabal'];
              $depSel = old('departamento', $paciente->departamento);
            @endphp
            <select name="departamento" class="form-select" required>
              <option value="">Seleccione</option>
              @foreach($deps as $dep)
                <option value="{{ $dep }}" {{ $depSel==$dep?'selected':'' }}>{{ $dep }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-md-4">
            <label class="form-label">Municipio</label>
            @php
              $muns = ['Guatemala','Mixco','Villa Nueva','Jocotenango','Chinautla','Escuintla'];
              $munSel = old('municipio', $paciente->municipio);
            @endphp
            <select name="municipio" class="form-select" required>
              <option value="">Seleccione</option>
              @foreach($muns as $mun)
                <option value="{{ $mun }}" {{ $munSel==$mun?'selected':'' }}>{{ $mun }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-md-4">
            <label class="form-label">Tipo de Consulta</label>
            @php $tcSel = old('tipo_consulta', $paciente->tipo_consulta); @endphp
            <select name="tipo_consulta" class="form-select" required>
              <option value="">Seleccione</option>
              <option value="CONSULTA GENERAL" {{ $tcSel=='CONSULTA GENERAL'?'selected':'' }}>CONSULTA GENERAL</option>
              <option value="CONSULTA ESPECIALIZADA" {{ $tcSel=='CONSULTA ESPECIALIZADA'?'selected':'' }}>CONSULTA ESPECIALIZADA</option>
            </select>
          </div>

          <div class="col-md-4">
            <label class="form-label">Organización</label>
            @php $orgSel = old('empresa', $paciente->empresa); @endphp
            <select name="empresa" class="form-select" required>
              <option value="">Seleccione</option>
              <option value="EMPRESA" {{ $orgSel=='EMPRESA'?'selected':'' }}>EMPRESA</option>
              <option value="MUNICIPALIDAD" {{ $orgSel=='MUNICIPALIDAD'?'selected':'' }}>MUNICIPALIDAD</option>
              <option value="REFIRIENTE" {{ $orgSel=='REFIRIENTE'?'selected':'' }}>REFIRIENTE</option>
            </select>
          </div>

          <div class="col-md-12">
            <label class="form-label">Nombre de la Empresa</label>
            <input type="text" name="nombre_empresa" class="form-control" required
                   value="{{ old('nombre_empresa', $paciente->nombre_empresa) }}">
          </div>
        </div>

        <h6 class="fw-semibold mb-3">📞 Datos del Referente</h6>

        <div class="row g-3 mb-4">
          <div class="col-md-6">
            <label class="form-label">Referido por</label>
            <input type="text" name="referido_por" class="form-control"
                   value="{{ old('referido_por', $paciente->referido_por) }}">
          </div>

          <div class="col-md-3">
            <label class="form-label">Teléfono</label>
            <input type="text" name="telefono_referente" class="form-control"
                   value="{{ old('telefono_referente', $paciente->telefono_referente) }}">
          </div>

          <div class="col-md-4">
            <label class="form-label">Tipo de Contacto</label>
            @php
              $tcs = ['Call Center','Celular Personal','Redes Sociales','Referencia Personal'];
              $tconSel = old('tipo_contacto', $paciente->tipo_contacto);
            @endphp
            <select name="tipo_contacto" class="form-select" required>
              <option value="">Seleccione</option>
              @foreach($tcs as $tc)
                <option value="{{ $tc }}" {{ $tconSel==$tc?'selected':'' }}>{{ $tc }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-md-4">
            <label class="form-label">Tipo de Consulta (Referente)</label>
            @php $tcrSel = old('tipo_consulta_referente', $paciente->tipo_consulta_referente); @endphp
            <select name="tipo_consulta_referente" class="form-select">
              <option value="">Seleccione</option>
              <option value="CONSULTA GENERAL" {{ $tcrSel=='CONSULTA GENERAL'?'selected':'' }}>CONSULTA GENERAL</option>
              <option value="CONSULTA ESPECIALIZADA" {{ $tcrSel=='CONSULTA ESPECIALIZADA'?'selected':'' }}>CONSULTA ESPECIALIZADA</option>
            </select>
          </div>

          <div class="col-md-12">
            <label class="form-label">Observaciones</label>
            <textarea name="descripcion" class="form-control" rows="3">{{ old('descripcion', $paciente->descripcion) }}</textarea>
          </div>
        </div>

        {{-- BOTONES (SIN FORM ANIDADO) --}}
        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('pacientes.index') }}"
            class="btn btn-outline-secondary">
               Cancelar
            </a>

          <button type="submit" class="btn btn-primary">
            💾 Guardar cambios
          </button>
        </div>

      </form>



    </div>
  </div>

</div>
@endsection
