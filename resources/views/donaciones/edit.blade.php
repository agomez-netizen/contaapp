@extends('layouts.app')

@section('title', 'Editar Donación')

@section('content')
<div class="container py-4">

  <div class="card shadow-sm border-0">
    <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
      <h5 class="mb-0">Editar Donación #{{ $donacion->id_donacion }}</h5>
      <a href="{{ route('index') }}" class="btn btn-sm btn-light">Volver</a>
    </div>

    <div class="card-body">
      <form method="POST" action="{{ route('donaciones.update', $donacion->id_donacion) }}">
        @csrf
        @method('PUT')

        {{-- ERRORES --}}
        @if ($errors->any())
          <div class="alert alert-danger">
            <ul class="mb-0">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        {{-- ================= DATOS PRINCIPALES ================= --}}
        <h6 class="text-primary mb-3">Datos de la Donación</h6>

        <div class="row g-3">
          <div class="col-md-3">
            <label class="form-label">Fecha despachada</label>
            <input type="date" name="fecha_despachada" class="form-control"
                   value="{{ old('fecha_despachada', $donacion->fecha_despachada) }}">
          </div>

          <div class="col-md-3">
            <label class="form-label">Empresa</label>
            <input type="text" name="empresa" class="form-control"
                   value="{{ old('empresa', $donacion->empresa) }}">
          </div>

          <div class="col-md-3">
            <label class="form-label">NIT</label>
            <input type="text" name="nit" class="form-control"
                   value="{{ old('nit', $donacion->nit) }}">
          </div>

          <div class="col-md-3">
            <label class="form-label">Contacto</label>
            <input type="text" name="contacto" class="form-control"
                   value="{{ old('contacto', $donacion->contacto) }}">
          </div>

          <div class="col-md-3">
            <label class="form-label">Teléfono</label>
            <input type="text" name="telefono" class="form-control"
                   value="{{ old('telefono', $donacion->telefono) }}">
          </div>

          <div class="col-md-3">
            <label class="form-label">Correo</label>
            <input type="email" name="correo" class="form-control"
                   value="{{ old('correo', $donacion->correo) }}">
          </div>

          <div class="col-md-3">
            <label class="form-label">Unidades</label>
            <input id="unidades" type="number" name="unidades" class="form-control" min="0"
                   value="{{ old('unidades', $donacion->unidades) }}">
          </div>

          <div class="col-md-6">
            <label class="form-label">Descripción</label>
            <textarea name="descripcion" class="form-control" rows="2">{{ old('descripcion', $donacion->descripcion) }}</textarea>
          </div>

          <div class="col-md-3">
            <label class="form-label">Valor total donación</label>
            {{-- Visible (formateado) --}}
            <input
              id="valor_total_donacion_display"
              type="text"
              class="form-control money-display"
              inputmode="decimal"
              placeholder="Q 0.00"
              value="{{ old('valor_total_donacion', $donacion->valor_total_donacion) }}"
            >
            {{-- Real (numérico limpio) --}}
            <input
              id="valor_total_donacion"
              type="hidden"
              name="valor_total_donacion"
              value="{{ old('valor_total_donacion', $donacion->valor_total_donacion) }}"
            >
          </div>
        </div>

        {{-- ================= ENTREGA ================= --}}
        <hr class="my-4">
        <h6 class="text-primary mb-3">Entrega y Recepción</h6>

        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label">Ubicar en</label>
            <select name="id_ubicacion" class="form-select">
              <option value="">Seleccione...</option>
              @foreach($ubicaciones as $u)
                <option value="{{ $u->id_ubicacion }}"
                  {{ (string)old('id_ubicacion', $donacion->id_ubicacion) === (string)$u->id_ubicacion ? 'selected' : '' }}>
                  {{ $u->nombre }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="col-md-3">
            <label class="form-label">Fecha que recibe</label>
            <input type="date" name="fecha_recibe" class="form-control"
                   value="{{ old('fecha_recibe', $donacion->fecha_recibe) }}">
          </div>

          <div class="col-md-3">
            <label class="form-label">Quién recibe</label>
            <input type="text" name="quien_recibe" class="form-control"
                   value="{{ old('quien_recibe', $donacion->quien_recibe) }}">
          </div>

          <div class="col-md-3">
            <label class="form-label">Tipo de donación</label>
            <select name="id_tipo_donacion" class="form-select">
              <option value="">Seleccione...</option>
              @foreach($tipos as $t)
                <option value="{{ $t->id_tipo_donacion }}"
                  {{ (string)old('id_tipo_donacion', $donacion->id_tipo_donacion) === (string)$t->id_tipo_donacion ? 'selected' : '' }}>
                  {{ $t->nombre }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="col-md-2">
            <label class="form-label">Unidades Recibidas</label>
            {{-- ✅ id agregado para autocalculo --}}
            <input id="unidades_entrega" type="number" name="unidades_entrega" class="form-control" min="0"
                   value="{{ old('unidades_entrega', $donacion->unidades_entrega) }}">
          </div>

          <div class="col-md-4">
            <label class="form-label">Persona que gestionó</label>
            <input type="text" name="persona_gestiono" class="form-control"
                   value="{{ old('persona_gestiono', $donacion->persona_gestiono) }}">
          </div>
        </div>

        {{-- ================= COSTOS ================= --}}
        <hr class="my-4">
        <h6 class="text-primary mb-3">Costos y Mercado</h6>

        <div class="row g-3">
          <div class="col-md-3">
            <label class="form-label">Precio mercado unidad</label>
            {{-- Visible (formateado) --}}
            <input
              id="precio_mercado_unidad_display"
              type="text"
              class="form-control money-display"
              inputmode="decimal"
              placeholder="Q 0.00"
              value="{{ old('precio_mercado_unidad', $donacion->precio_mercado_unidad) }}"
            >
            {{-- Real (numérico limpio) --}}
            <input
              id="precio_mercado_unidad"
              type="hidden"
              name="precio_mercado_unidad"
              value="{{ old('precio_mercado_unidad', $donacion->precio_mercado_unidad) }}"
            >
          </div>

          <div class="col-md-3">
            <label class="form-label">Total</label>
            {{-- Visible (formateado) --}}
            <input
              id="total_mercado_display"
              type="text"
              class="form-control"
              readonly
              tabindex="-1"
              placeholder="Q 0.00"
              value="{{ old('total_mercado', $donacion->total_mercado) }}"
            >
            {{-- Real (numérico limpio) --}}
            <input
              id="total_mercado"
              type="hidden"
              name="total_mercado"
              value="{{ old('total_mercado', $donacion->total_mercado) }}"
            >
            {{-- ✅ texto corregido --}}
            <div class="form-text">Se calcula automáticamente: Precio mercado unidad × Unidades entrega.</div>
          </div>

          <div class="col-md-4">
            <label class="form-label">Referencia del mercado</label>
            <input type="text" name="referencia_mercado" class="form-control"
                   value="{{ old('referencia_mercado', $donacion->referencia_mercado) }}">
          </div>

          <div class="col-md-3">
            <label class="form-label">Costo logística</label>
            {{-- Visible (formateado) --}}
            <input
              id="costo_logistica_display"
              type="text"
              class="form-control money-display"
              inputmode="decimal"
              placeholder="Q 0.00"
              value="{{ old('costo_logistica', $donacion->costo_logistica) }}"
            >
            {{-- Real (numérico limpio) --}}
            <input
              id="costo_logistica"
              type="hidden"
              name="costo_logistica"
              value="{{ old('costo_logistica', $donacion->costo_logistica) }}"
            >
          </div>

          <div class="col-md-6">
            <label class="form-label">Descripción logística</label>
            <textarea name="descripcion_logistica" class="form-control" rows="2">{{ old('descripcion_logistica', $donacion->descripcion_logistica) }}</textarea>
          </div>
        </div>

        {{-- ================= PROYECTO / IMPACTO ================= --}}
        <hr class="my-4">
        <h6 class="text-primary mb-3">Proyecto e Impacto</h6>

        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label">Proyecto asignado</label>
            <select name="id_proyecto" class="form-select">
              <option value="">Seleccione...</option>
              @foreach($proyectos as $p)
                <option value="{{ $p->id_proyecto }}"
                  {{ (string)old('id_proyecto', $donacion->id_proyecto) === (string)$p->id_proyecto ? 'selected' : '' }}>
                  {{ $p->nombre }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="col-md-3">
            <label class="form-label">Impacto (personas)</label>
            <input type="number" name="impacto_personas" class="form-control" min="0"
                   value="{{ old('impacto_personas', $donacion->impacto_personas) }}">
          </div>

          <div class="col-md-6">
            <label class="form-label">Comentarios</label>
            <textarea name="comentarios" class="form-control" rows="2">{{ old('comentarios', $donacion->comentarios) }}</textarea>
          </div>
        </div>

        {{-- ================= DOCUMENTOS / REFERENCIAS ================= --}}
        <hr class="my-4">
        <h6 class="text-primary mb-3">Documentos y Referencias</h6>

        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label">Recibo de empresa</label>
            <select name="recibo_empresa" class="form-select">
              <option value="" disabled {{ old('recibo_empresa', $donacion->recibo_empresa) === null ? 'selected' : '' }}>Seleccione...</option>
              <option value="1" {{ (string)old('recibo_empresa', $donacion->recibo_empresa) === '1' ? 'selected' : '' }}>Sí (Existe)</option>
              <option value="0" {{ (string)old('recibo_empresa', $donacion->recibo_empresa) === '0' ? 'selected' : '' }}>No (No existe)</option>
            </select>
          </div>
        </div>

        <div class="row g-3 mt-1">
          <div class="col-md-6">
            <label class="form-label">No. Referencia OSSHP</label>
            <input type="text" name="ref_osshp" class="form-control"
                   value="{{ old('ref_osshp', $donacion->ref_osshp) }}">
          </div>

          <div class="col-md-6">
            <label class="form-label">Fecha referencia OSSHP</label>
            <input type="date" name="fecha_ref_osshp" class="form-control"
                   value="{{ old('fecha_ref_osshp', $donacion->fecha_ref_osshp) }}">
          </div>

          <div class="col-md-6">
            <label class="form-label">No. Referencia SAT</label>
            <input type="text" name="ref_sat" class="form-control"
                   value="{{ old('ref_sat', $donacion->ref_sat) }}">
          </div>

          <div class="col-md-6">
            <label class="form-label">Fecha referencia SAT</label>
            <input type="date" name="fecha_ref_sat" class="form-control"
                   value="{{ old('fecha_ref_sat', $donacion->fecha_ref_sat) }}">
          </div>
        </div>

        <div class="mt-4 d-flex justify-content-end gap-2">
          <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Cancelar</a>
          <button type="submit" class="btn btn-primary px-4">Guardar Cambios</button>
        </div>

      </form>
    </div>
  </div>

</div>

{{-- ==== JS: Moneda + Autocalculo ==== --}}
<script>
(function () {
  // Convierte cualquier entrada a número (ej: "Q 55,000.00" -> 55000)
  function parseMoney(val) {
    if (val === null || val === undefined) return 0;
    const s = String(val).trim();
    if (!s) return 0;
    // deja solo dígitos, punto y signo -
    const cleaned = s.replace(/[^0-9.\-]/g, '');
    const n = parseFloat(cleaned);
    return isNaN(n) ? 0 : n;
  }

  // Formatea en quetzales: Q 55,000.00
  function formatGTQ(num) {
    const n = (isNaN(num) || num === null) ? 0 : Number(num);
    return 'Q ' + n.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
  }

  // Vincula un input visible (display) con uno hidden (real)
  function bindMoneyPair(displayEl, hiddenEl) {
    if (!displayEl || !hiddenEl) return;

    // Inicializa (por si viene numérico desde backend)
    const initial = parseMoney(hiddenEl.value || displayEl.value);
    hiddenEl.value = initial ? String(initial) : String(parseMoney(displayEl.value));
    displayEl.value = formatGTQ(parseMoney(hiddenEl.value));

    function syncFromDisplay() {
      const n = parseMoney(displayEl.value);
      hiddenEl.value = String(n);
    }

    displayEl.addEventListener('input', syncFromDisplay);

    displayEl.addEventListener('blur', function () {
      const n = parseMoney(displayEl.value);
      hiddenEl.value = String(n);
      displayEl.value = formatGTQ(n);
    });

    displayEl.addEventListener('focus', function () {
      // al enfocar, mostramos un número "editable" sin Q ni comas para que sea cómodo
      const n = parseMoney(hiddenEl.value);
      displayEl.value = (n === 0) ? '' : String(n.toFixed(2));
      // Selecciona todo (porque la vida es corta)
      setTimeout(() => displayEl.select(), 0);
    });
  }

  function recalcTotal() {
    // ✅ aquí cambia: usamos unidades_entrega (no unidades)
    const unidadesEl = document.getElementById('unidades_entrega');
    const precioHidden = document.getElementById('precio_mercado_unidad');
    const totalHidden  = document.getElementById('total_mercado');
    const totalDisplay = document.getElementById('total_mercado_display');

    if (!unidadesEl || !precioHidden || !totalHidden || !totalDisplay) return;

    const unidades = parseMoney(unidadesEl.value);
    const precio   = parseMoney(precioHidden.value);
    const total    = (unidades * precio);

    totalHidden.value = String(total);
    totalDisplay.value = formatGTQ(total);
  }

  // Bind de campos moneda
  bindMoneyPair(
    document.getElementById('valor_total_donacion_display'),
    document.getElementById('valor_total_donacion')
  );
  bindMoneyPair(
    document.getElementById('precio_mercado_unidad_display'),
    document.getElementById('precio_mercado_unidad')
  );
  bindMoneyPair(
    document.getElementById('costo_logistica_display'),
    document.getElementById('costo_logistica')
  );

  // Inicializa Total con formato y autocalcula
  (function initTotal() {
    const totalHidden  = document.getElementById('total_mercado');
    const totalDisplay = document.getElementById('total_mercado_display');
    if (totalHidden && totalDisplay) {
      totalDisplay.value = formatGTQ(parseMoney(totalHidden.value || totalDisplay.value));
    }
    recalcTotal();
  })();

  // ✅ Recalcular cuando cambie unidades_entrega o precio
  const unidadesEntregaEl = document.getElementById('unidades_entrega');
  const precioDisplay = document.getElementById('precio_mercado_unidad_display');

  if (unidadesEntregaEl) {
    unidadesEntregaEl.addEventListener('input', recalcTotal);
    unidadesEntregaEl.addEventListener('blur', recalcTotal);
  }
  if (precioDisplay) {
    precioDisplay.addEventListener('input', recalcTotal);
    precioDisplay.addEventListener('blur', recalcTotal);
  }
})();
</script>
@endsection
