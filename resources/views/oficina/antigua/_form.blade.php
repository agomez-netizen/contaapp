@csrf

<div class="row g-3">

  <div class="col-md-4">
    <label class="form-label">Tipo Documento</label>
    <select name="tipo_documento" class="form-select" required>
      <option value="">Seleccione...</option>
      <option value="FACTURA"
        {{ old('tipo_documento', $row->tipo_documento ?? '')=='FACTURA'?'selected':'' }}>
        Factura
      </option>
      <option value="COTIZACION"
        {{ old('tipo_documento', $row->tipo_documento ?? '')=='COTIZACION'?'selected':'' }}>
        Cotización
      </option>
     <option value="RECIBO"
        {{ old('tipo_documento', $row->tipo_documento ?? '')=='RECIBO'?'selected':'' }}>
        Recibo
      </option>
    </select>
  </div>

  <div class="col-md-4">
    <label class="form-label">Proyecto</label>
    <select name="id_proyecto" class="form-select" required>
      <option value="">Seleccione...</option>
      @foreach($proyectos as $p)
        <option value="{{ $p->id_proyecto }}"
          {{ (string)old('id_proyecto', $row->id_proyecto ?? '') === (string)$p->id_proyecto ? 'selected' : '' }}>
          {{ $p->nombre }}
        </option>
      @endforeach
    </select>
  </div>

  <div class="col-md-4">
    <label class="form-label">Rubro</label>
    <select name="id_rubro" class="form-select">
      <option value="">Seleccione...</option>
      @foreach($rubros as $rb)
        <option value="{{ $rb->id_rubro }}"
          {{ (string)old('id_rubro', $row->id_rubro ?? '') === (string)$rb->id_rubro ? 'selected' : '' }}>
          {{ $rb->nombre }}
        </option>
      @endforeach
    </select>
  </div>

<div class="col-md-4">
  <label class="form-label">Fecha Documento</label>
  <input type="date" name="fecha_documento" class="form-control"
         value="{{ old('fecha_documento', !empty($row->fecha_documento) ? \Carbon\Carbon::parse($row->fecha_documento)->format('Y-m-d') : '') }}"
         required>
</div>


  <div class="col-md-4">
    <label class="form-label">No. Documento</label>
    <input type="text" name="no_documento" class="form-control"
           value="{{ old('no_documento', $row->no_documento ?? '') }}">
  </div>

  <div class="col-md-4">
    <label class="form-label">Serie</label>
    <input type="text" name="serie" class="form-control"
           value="{{ old('serie', $row->serie ?? '') }}">
  </div>

  <div class="col-md-6">
    <label class="form-label">Empresa</label>
    <input type="text" name="empresa_nombre" class="form-control"
           value="{{ old('empresa_nombre', $row->empresa_nombre ?? '') }}">
  </div>

  <div class="col-md-3">
    <label class="form-label">NIT</label>
    <input type="text" name="nit" class="form-control"
           value="{{ old('nit', $row->nit ?? '') }}">
  </div>

  <div class="col-md-3">
    <label class="form-label">Teléfono</label>
    <input type="text" name="telefono" class="form-control"
           value="{{ old('telefono', $row->telefono ?? '') }}">
  </div>

  <div class="col-md-12">
    <label class="form-label">Dirección</label>
    <input type="text" name="direccion" class="form-control"
           value="{{ old('direccion', $row->direccion ?? '') }}">
  </div>

  <div class="col-md-6">
    <label class="form-label">Correo</label>
    <input type="text" name="correo" class="form-control"
           value="{{ old('correo', $row->correo ?? '') }}">
  </div>

  <div class="col-md-6">
    <label class="form-label">Contacto</label>
    <input type="text" name="contacto" class="form-control"
           value="{{ old('contacto', $row->contacto ?? '') }}">
  </div>

  <div class="col-md-4">
    <label class="form-label">Descuento</label>
    <input type="number" step="0.01" name="descuento" class="form-control"
           value="{{ old('descuento', $row->descuento ?? 0) }}">
  </div>

  <div class="col-md-4">
    <label class="form-label">Monto</label>
    <input type="number" step="0.01" name="monto" class="form-control"
           value="{{ old('monto', $row->monto ?? '') }}" required>
  </div>

  <div class="col-md-4 d-flex align-items-end">
    <div class="form-check">
      <input id="chk_pagada" class="form-check-input" type="checkbox" name="pagada" value="1"
        {{ old('pagada', $row->pagada ?? 0) ? 'checked' : '' }}>
      <label class="form-check-label" for="chk_pagada">Pagada</label>
    </div>
  </div>

  {{-- ================== SECCIÓN PAGO ================== --}}
  <div class="col-12 mt-4">
    <hr>
    <h5 class="fw-bold mb-0">Información de Pago</h5>
    <div class="text-muted">Se habilita automáticamente al marcar “Pagada”.</div>
  </div>

  <div class="col-md-6">
    <label class="form-label">No. Documento (Cheque/Transferencia/Deposito)</label>
    <input id="no_documento_pago" type="text" name="no_documento_pago" class="form-control"
           value="{{ old('no_documento_pago', $row->no_documento_pago ?? '') }}">
  </div>

<div class="col-md-6">
  <label class="form-label">Fecha Pago</label>
  <input id="fecha_pago" type="date" name="fecha_pago" class="form-control"
         value="{{ old('fecha_pago', !empty($row->fecha_pago) ? \Carbon\Carbon::parse($row->fecha_pago)->format('Y-m-d') : '') }}">
</div>

  {{-- =================================================== --}}

  {{-- ARCHIVO --}}
  <div class="col-12">
    <label class="form-label">Cargar Documento (PDF / JPG / PNG)</label>
    <input type="file" name="archivo" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
    <div class="form-text">Máx. 5MB</div>

    @if(!empty($row->archivo_path))
      <div class="mt-2">
        <a href="{{ asset($row->archivo_path) }}" target="_blank"
           class="btn btn-sm btn-outline-dark">
          Ver archivo actual
        </a>
        <span class="text-muted ms-2">{{ $row->archivo_original }}</span>
      </div>
    @endif
  </div>

  <div class="col-12">
    <label class="form-label">Descripción</label>
    <textarea name="descripcion" class="form-control" rows="3">{{ old('descripcion', $row->descripcion ?? '') }}</textarea>
  </div>

</div>

{{-- JS: Habilitar/Deshabilitar sección pago según checkbox --}}
<script>
(function () {
  function setPagoEnabled(enabled) {
    const noDoc = document.getElementById('no_documento_pago');
    const fPago = document.getElementById('fecha_pago');

    if (!noDoc || !fPago) return;

    noDoc.disabled = !enabled;
    fPago.disabled = !enabled;

    // Si se desmarca, limpiamos para que no se guarde basura
    if (!enabled) {
      noDoc.value = '';
      fPago.value = '';
    }
  }

  document.addEventListener('DOMContentLoaded', function () {
    const chk = document.getElementById('chk_pagada');
    if (!chk) return;

    // Estado inicial
    setPagoEnabled(chk.checked);

    // Cambio
    chk.addEventListener('change', function () {
      setPagoEnabled(chk.checked);
    });
  });
})();
</script>
