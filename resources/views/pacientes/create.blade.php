@extends('layouts.app')

@section('title', 'Registrar Paciente')

@section('content')
<div class="container py-4">

  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h3 class="fw-bold mb-1">🧑‍⚕️ Registro de Paciente</h3>
      <div class="text-muted">Formulario de ingreso de pacientes</div>
    </div>

        {{-- ✅ Botón Volver --}}
    <a href="{{ route('pacientes.index') }}" class="btn btn-outline-secondary">
      ← Volver
    </a>
  </div>

  {{-- Mensajes --}}
    @if(session('ok'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" id="successAlert">
        <strong>✔</strong> {{ session('ok') }}

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>

    <script>
        setTimeout(() => {
        const alert = document.getElementById('successAlert');
        if (alert) {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
            bsAlert.close();
        }
        }, 3000); // 4 segundos
    </script>
    @endif



  @if($errors->any())
    <div class="alert alert-danger">
      <strong>Corrige los siguientes errores:</strong>
      <ul class="mb-0">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="card border-0 shadow-sm">
    <div class="card-body">

      <form method="POST" action="{{ route('pacientes.store') }}">
        @csrf

        {{-- =========================
        DATOS DEL PACIENTE
        ========================== --}}
        <h6 class="fw-semibold mb-3">📋 Datos del Paciente</h6>

        <div class="row g-3 mb-4">

          <div class="col-md-6">
            <label class="form-label">Nombre del Paciente</label>
            <input type="text" name="nombre" class="form-control" required value="{{ old('nombre') }}">
          </div>

          <div class="col-md-6">
            <label class="form-label">DPI</label>
            <input type="text" name="dpi" class="form-control" required value="{{ old('dpi') }}">
          </div>

          <div class="col-md-4">
            <label class="form-label">Sexo</label>
            <select name="sexo" class="form-select" required>
              <option value="">Seleccione</option>
              <option value="MASCULINO" {{ old('sexo')=='MASCULINO'?'selected':'' }}>MASCULINO</option>
              <option value="FEMENINO" {{ old('sexo')=='FEMENINO'?'selected':'' }}>FEMENINO</option>
            </select>
          </div>

          <div class="col-md-4">
            <label class="form-label">Edad</label>
            <input type="number" name="edad" class="form-control" min="0" required value="{{ old('edad') }}">
          </div>

          <div class="col-md-4">
            <label class="form-label">No. Carnet</label>
            <input type="text" name="carnet" class="form-control" value="{{ old('carnet') }}">
          </div>

          <div class="col-md-4">
            <label class="form-label">Teléfono</label>
            <input type="text" name="telefono" class="form-control" value="{{ old('telefono') }}">
          </div>

          <div class="col-md-4">
            <label class="form-label">Correo Electrónico</label>
            <input type="email" name="correo" class="form-control" value="{{ old('correo') }}">
          </div>

          <div class="col-md-4">
            <label class="form-label">Departamento</label>
            <select name="departamento" class="form-select" required>
              <option value="">Seleccione</option>
              @foreach(['Guatemala','El Progreso','Sacatepéquez','Chimaltenango','Escuintla','Santa Rosa','Sololá','Totonicapán','Quetzaltenango','Suchitepéquez','Retalhuleu','San Marcos','Huehuetenango','Quiché','Baja Verapaz','Alta Verapaz','Petén','Izabal','Zacapa','Chiquimula','Jalapa','Jutiapa'] as $dep)
                <option value="{{ $dep }}" {{ old('departamento')==$dep?'selected':'' }}>{{ $dep }}</option>
              @endforeach
            </select>
          </div>

<div class="col-md-4">
    <label class="form-label">Municipio</label>
    <input
        type="text"
        name="municipio"
        class="form-control"
        value="{{ old('municipio') }}"
        placeholder="Ingrese el municipio"
        required
    >
</div>


          <div class="col-md-4">
            <label class="form-label">Tipo de Consulta</label>
            <select name="tipo_consulta" class="form-select" required>
              <option value="">Seleccione</option>
              <option value="CONSULTA GENERAL" {{ old('tipo_consulta')=='CONSULTA GENERAL'?'selected':'' }}>CONSULTA GENERAL</option>
              <option value="CONSULTA ESPECIALIZADA" {{ old('tipo_consulta')=='CONSULTA ESPECIALIZADA'?'selected':'' }}>CONSULTA ESPECIALIZADA</option>
            </select>
          </div>

          <div class="col-md-4">
            <label class="form-label">Organización</label>
            <select name="empresa" class="form-select" required>
              <option value="">Seleccione</option>
              <option value="EMPRESA" {{ old('empresa')=='EMPRESA'?'selected':'' }}>EMPRESA</option>
              <option value="MUNICIPALIDAD" {{ old('empresa')=='MUNICIPALIDAD'?'selected':'' }}>MUNICIPALIDAD</option>
              <option value="REFIRIENTE" {{ old('empresa')=='REFIRIENTE'?'selected':'' }}>REFIRIENTE</option>
            </select>
          </div>

          <div class="col-md-12">
            <label class="form-label">Nombre de la Empresa</label>
            <input type="text" name="nombre_empresa" class="form-control" required value="{{ old('nombre_empresa') }}">
          </div>

        </div>

        {{-- =========================
        DATOS DEL REFERENTE
        ========================== --}}
        <h6 class="fw-semibold mb-3">📞 Datos del Referente</h6>

        <div class="row g-3 mb-4">

          <div class="col-md-6">
            <label class="form-label">Referido por</label>
            <input type="text" name="referido_por" class="form-control" value="{{ old('referido_por') }}">
          </div>

          <div class="col-md-3">
            <label class="form-label">Teléfono</label>
            <input type="text" name="telefono_referente" class="form-control" value="{{ old('telefono_referente') }}">
          </div>

          <div class="col-md-4">
            <label class="form-label">Tipo de Contacto</label>
            <select name="tipo_contacto" class="form-select" required>
              <option value="">Seleccione</option>
              @foreach(['Call Center','Celular Personal','Redes Sociales','Referencia Personal'] as $tc)
                <option value="{{ $tc }}" {{ old('tipo_contacto')==$tc?'selected':'' }}>{{ $tc }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-md-4">
            <label class="form-label">Tipo de Consulta (Referente)</label>
            <select name="tipo_consulta_referente" class="form-select">
              <option value="">Seleccione</option>
              <option value="CONSULTA GENERAL" {{ old('tipo_consulta_referente')=='CONSULTA GENERAL'?'selected':'' }}>CONSULTA GENERAL</option>
              <option value="CONSULTA ESPECIALIZADA" {{ old('tipo_consulta_referente')=='CONSULTA ESPECIALIZADA'?'selected':'' }}>CONSULTA ESPECIALIZADA</option>
            </select>
          </div>

          <div class="col-md-12">
            <label class="form-label">Observaciones</label>
            <textarea name="descripcion" class="form-control" rows="3">{{ old('descripcion') }}</textarea>
          </div>

        </div>

        <div class="d-flex justify-content-end gap-2">
          <button type="reset" class="btn btn-outline-secondary">Limpiar</button>
          <button type="submit" class="btn btn-primary">Guardar Paciente</button>
        </div>

      </form>

    </div>
  </div>

</div>
@endsection
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
