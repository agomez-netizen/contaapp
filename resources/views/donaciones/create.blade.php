@extends('layouts.app')

@section('title', 'Ingresar Donación')

@section('content')
<div class="container py-4">

  @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
  @endif

  @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      {{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
  @endif

  <div class="card shadow-sm border-0">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
      <h5 class="mb-0">Registrar Donación</h5>

      <a href="{{ route('donaciones.index') }}" class="btn btn-light btn-sm">
        ← Volver
      </a>
    </div>

    <div class="card-body">
      <form method="POST" action="{{ route('donaciones.store') }}">
        @csrf

        {{-- ================= DATOS PRINCIPALES ================= --}}
        <h6 class="text-success mb-3">Datos de la Donación</h6>

        <div class="row g-3">
          <div class="col-md-3">
            <label class="form-label">Fecha despachada</label>
            <input type="date" name="fecha_despachada" class="form-control @error('fecha_despachada') is-invalid @enderror"
                   value="{{ old('fecha_despachada') }}">
            @error('fecha_despachada') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-3">
            <label class="form-label">Empresa</label>
            <input type="text" name="empresa" class="form-control @error('empresa') is-invalid @enderror"
                   value="{{ old('empresa') }}">
            @error('empresa') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-3">
            <label class="form-label">NIT</label>
            <input type="text" name="nit" class="form-control @error('nit') is-invalid @enderror"
                   value="{{ old('nit') }}">
            @error('nit') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-3">
            <label class="form-label">Contacto</label>
            <input type="text" name="contacto" class="form-control @error('contacto') is-invalid @enderror"
                   value="{{ old('contacto') }}">
            @error('contacto') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-3">
            <label class="form-label">Teléfono</label>
            <input type="text" name="telefono" class="form-control @error('telefono') is-invalid @enderror"
                   value="{{ old('telefono') }}">
            @error('telefono') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-3">
            <label class="form-label">Correo</label>
            <input type="email" name="correo" class="form-control @error('correo') is-invalid @enderror"
                   value="{{ old('correo') }}">
            @error('correo') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-3">
            <label class="form-label">Unidades</label>
            <input type="number" name="unidades" class="form-control @error('unidades') is-invalid @enderror" min="0"
                   value="{{ old('unidades') }}">
            @error('unidades') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-6">
            <label class="form-label">Descripción</label>
            <textarea name="descripcion" class="form-control @error('descripcion') is-invalid @enderror" rows="2">{{ old('descripcion') }}</textarea>
            @error('descripcion') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-3">
            <label class="form-label">Valor total donación</label>
            <input type="number" step="0.01" name="valor_total_donacion"
                   class="form-control @error('valor_total_donacion') is-invalid @enderror" min="0"
                   value="{{ old('valor_total_donacion') }}">
            @error('valor_total_donacion') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
        </div>

        {{-- ================= ENTREGA ================= --}}
        <hr class="my-4">
        <h6 class="text-success mb-3">Entrega y Recepción</h6>

        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label">Ubicar en</label>
            <select name="id_ubicacion" class="form-select @error('id_ubicacion') is-invalid @enderror">
              <option value="" disabled {{ old('id_ubicacion') ? '' : 'selected' }}>Seleccione...</option>
              @foreach(($ubicaciones ?? []) as $u)
                <option value="{{ $u->id_ubicacion }}" {{ (string)old('id_ubicacion') === (string)$u->id_ubicacion ? 'selected' : '' }}>
                  {{ $u->nombre }}
                </option>
              @endforeach
            </select>
            @error('id_ubicacion') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-3">
            <label class="form-label">Fecha que recibe</label>
            <input type="date" name="fecha_recibe" class="form-control @error('fecha_recibe') is-invalid @enderror"
                   value="{{ old('fecha_recibe') }}">
            @error('fecha_recibe') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-3">
            <label class="form-label">Quién recibe</label>
            <input type="text" name="quien_recibe" class="form-control @error('quien_recibe') is-invalid @enderror"
                   value="{{ old('quien_recibe') }}">
            @error('quien_recibe') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-3">
            <label class="form-label">Tipo de donación</label>
            <select name="id_tipo_donacion" class="form-select @error('id_tipo_donacion') is-invalid @enderror">
              <option value="" disabled {{ old('id_tipo_donacion') ? '' : 'selected' }}>Seleccione...</option>
              @foreach(($tipos ?? []) as $t)
                <option value="{{ $t->id_tipo_donacion }}" {{ (string)old('id_tipo_donacion') === (string)$t->id_tipo_donacion ? 'selected' : '' }}>
                  {{ $t->nombre }}
                </option>
              @endforeach
            </select>
            @error('id_tipo_donacion') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-2">
            <label class="form-label">Unidades</label>
            <input type="number" name="unidades_entrega" class="form-control @error('unidades_entrega') is-invalid @enderror" min="0"
                   value="{{ old('unidades_entrega') }}">
            @error('unidades_entrega') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-4">
            <label class="form-label">Persona que gestionó</label>
            <input type="text" name="persona_gestiono" class="form-control @error('persona_gestiono') is-invalid @enderror"
                   value="{{ old('persona_gestiono') }}">
            @error('persona_gestiono') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
        </div>

        {{-- ================= COSTOS ================= --}}
        <hr class="my-4">
        <h6 class="text-success mb-3">Costos y Mercado</h6>

        <div class="row g-3">
          <div class="col-md-3">
            <label class="form-label">Precio mercado unidad</label>
            <input type="number" step="0.01" name="precio_mercado_unidad"
                   class="form-control @error('precio_mercado_unidad') is-invalid @enderror" min="0"
                   value="{{ old('precio_mercado_unidad') }}">
            @error('precio_mercado_unidad') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-3">
            <label class="form-label">Total</label>
            <input type="number" step="0.01" name="total_mercado"
                   class="form-control @error('total_mercado') is-invalid @enderror" min="0"
                   value="{{ old('total_mercado') }}">
            @error('total_mercado') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-4">
            <label class="form-label">Referencia del mercado</label>
            <input type="text" name="referencia_mercado" class="form-control @error('referencia_mercado') is-invalid @enderror"
                   value="{{ old('referencia_mercado') }}">
            @error('referencia_mercado') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-3">
            <label class="form-label">Costo logística</label>
            <input type="number" step="0.01" name="costo_logistica"
                   class="form-control @error('costo_logistica') is-invalid @enderror" min="0"
                   value="{{ old('costo_logistica') }}">
            @error('costo_logistica') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-6">
            <label class="form-label">Descripción logística</label>
            <textarea name="descripcion_logistica" class="form-control @error('descripcion_logistica') is-invalid @enderror" rows="2">{{ old('descripcion_logistica') }}</textarea>
            @error('descripcion_logistica') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
        </div>

        {{-- ================= PROYECTO / IMPACTO ================= --}}
        <hr class="my-4">
        <h6 class="text-success mb-3">Proyecto e Impacto</h6>

        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label">Proyecto asignado</label>
            <select name="id_proyecto" class="form-select @error('id_proyecto') is-invalid @enderror">
              <option value="" disabled {{ old('id_proyecto') ? '' : 'selected' }}>Seleccione...</option>
              @foreach(($proyectos ?? []) as $p)
                <option value="{{ $p->id_proyecto }}" {{ (string)old('id_proyecto') === (string)$p->id_proyecto ? 'selected' : '' }}>
                  {{ $p->nombre }}
                </option>
              @endforeach
            </select>
            @error('id_proyecto') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-3">
            <label class="form-label">Impacto (personas)</label>
            <input type="number" name="impacto_personas" class="form-control @error('impacto_personas') is-invalid @enderror" min="0"
                   value="{{ old('impacto_personas') }}">
            @error('impacto_personas') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-6">
            <label class="form-label">Comentarios</label>
            <textarea name="comentarios" class="form-control @error('comentarios') is-invalid @enderror" rows="2">{{ old('comentarios') }}</textarea>
            @error('comentarios') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
        </div>

        {{-- ================= DOCUMENTOS / REFERENCIAS ================= --}}
        <hr class="my-4">
        <h6 class="text-success mb-3">Documentos y Referencias</h6>

        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label">Recibo de empresa</label>
            <select name="recibo_empresa" class="form-select @error('recibo_empresa') is-invalid @enderror">
              <option value="" disabled {{ old('recibo_empresa', '') === '' ? 'selected' : '' }}>Seleccione...</option>
              <option value="1" {{ old('recibo_empresa') === '1' ? 'selected' : '' }}>Sí (Existe)</option>
              <option value="0" {{ old('recibo_empresa') === '0' ? 'selected' : '' }}>No (No existe)</option>
            </select>
            @error('recibo_empresa') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
        </div>

        <div class="row g-3 mt-1">
          <div class="col-md-6">
            <label class="form-label">No. Referencia OSSHP</label>
            <input type="text" name="ref_osshp" class="form-control @error('ref_osshp') is-invalid @enderror"
                   value="{{ old('ref_osshp') }}">
            @error('ref_osshp') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-6">
            <label class="form-label">Fecha referencia OSSHP</label>
            <input type="date" name="fecha_ref_osshp" class="form-control @error('fecha_ref_osshp') is-invalid @enderror"
                   value="{{ old('fecha_ref_osshp') }}">
            @error('fecha_ref_osshp') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-6">
            <label class="form-label">No. Referencia SAT</label>
            <input type="text" name="ref_sat" class="form-control @error('ref_sat') is-invalid @enderror"
                   value="{{ old('ref_sat') }}">
            @error('ref_sat') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-6">
            <label class="form-label">Fecha referencia SAT</label>
            <input type="date" name="fecha_ref_sat" class="form-control @error('fecha_ref_sat') is-invalid @enderror"
                   value="{{ old('fecha_ref_sat') }}">
            @error('fecha_ref_sat') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
        </div>

        {{-- ================= BOTÓN ================= --}}
        <div class="mt-4 text-end">
          <button type="submit" class="btn btn-primary px-4">
            Guardar Donación
          </button>
        </div>

      </form>
    </div>
  </div>

</div>
@endsection
