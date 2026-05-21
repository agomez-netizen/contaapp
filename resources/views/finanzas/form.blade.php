<div class="row g-3">

    <div class="col-md-4">
        <label class="form-label">Tipo Movimiento</label>
        <select name="tipo_movimiento" class="form-select" required>
            <option value="">Seleccione</option>
            <option value="INGRESO">Ingreso</option>
            <option value="EGRESO">Egreso</option>
        </select>
    </div>

    <div class="col-md-4">
        <label class="form-label">Tipo Documento</label>
        <select name="tipo_documento" class="form-select" required>
            <option value="">Seleccione</option>
            <option value="CARTA">Carta</option>
            <option value="CHEQUE">Cheque</option>
            <option value="COTIZACION">Cotización</option>
            <option value="DEPOSITO">Deposito</option>
            <option value="FACTURA">Factura</option>
            <option value="PRESUPUESTO">Presupuesto</option>
            <option value="RECIBO DE DONACION">Recibo</option>
            <option value="TRANSFERENCIA">Transferencia</option>
            <option value="OTRO">Otro</option>
        </select>
    </div>

    <div class="col-md-4">
        <label class="form-label">No. Documento</label>
        <input type="text" name="no_documento" class="form-control" placeholder="Ingrese número">
    </div>

    <div class="col-md-4">
        <label class="form-label">Fecha Documento</label>
        <input type="date" name="fecha_documento" class="form-control" required>
    </div>

    <div class="col-md-4">
        <label class="form-label">Proyecto</label>
        <select name="id_proyecto" id="id_proyecto" class="form-select" required>
            <option value="">Seleccione</option>
            @foreach($proyectos as $proyecto)
                <option value="{{ $proyecto->id_proyecto }}">{{ $proyecto->nombre }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <label class="form-label">Subproyecto</label>
        <select name="id_subproyecto" id="id_subproyecto" class="form-select">
            <option value="">Seleccione proyecto primero</option>
        </select>
    </div>

    <div class="col-md-4">
        <label class="form-label">Rubro</label>
        <select name="id_rubro" class="form-select">
            <option value="">Seleccione</option>
            @foreach($rubros as $rubro)
                <option value="{{ $rubro->id_rubro }}">{{ $rubro->nombre }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <label class="form-label">Empresa</label>
        <input type="text" name="empresa" class="form-control" placeholder="Nombre empresa">
    </div>

    <div class="col-md-4">
        <label class="form-label">Contacto</label>
        <input type="text" name="proveedor" class="form-control" placeholder="Nombre contacto">
    </div>

    <div class="col-md-4">
        <label class="form-label">Tipo de Cambio</label>
        <input type="number" step="0.0001" name="tipo_cambio" id="tipo_cambio" class="form-control" value="7.8000" required>
    </div>

    <div class="col-md-4">
        <label class="form-label">Monto en Quetzales</label>
        <input type="text" id="monto_quetzales_visible" class="form-control" placeholder="0.00">
        <input type="hidden" name="monto_quetzales" id="monto_quetzales">
        <input type="hidden" name="monto" id="monto">
    </div>

    <div class="col-md-4">
        <label class="form-label">Monto en Dólares</label>
        <input type="text" id="monto_dolares_visible" class="form-control" placeholder="0.00">
        <input type="hidden" name="monto_dolares" id="monto_dolares">
    </div>

    <div class="col-md-12">
        <label class="form-label">Descripción</label>
        <textarea name="descripcion" class="form-control" rows="4" placeholder="Ingrese descripción"></textarea>
    </div>

    <div class="col-md-12">
        <label class="form-label">Link en Drive</label>
        <input type="url" name="link_drive" class="form-control" placeholder="https://drive.google.com/...">
    </div>

    <div class="col-md-6">
        <label class="form-label">Cargar Documento</label>
        <input type="file" name="archivo" class="form-control">
    </div>

    <div class="col-md-12 mt-3">
        <button type="submit" class="btn btn-primary">
            Guardar Movimiento
        </button>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const tipoCambio = document.getElementById('tipo_cambio');
    const qVisible = document.getElementById('monto_quetzales_visible');
    const dVisible = document.getElementById('monto_dolares_visible');
    const qHidden = document.getElementById('monto_quetzales');
    const dHidden = document.getElementById('monto_dolares');
    const montoHidden = document.getElementById('monto');

    let ultimoEditado = 'quetzales';

    function limpiarNumero(valor) {
        return String(valor || '').replace(/,/g, '');
    }

    function numero(valor) {
        const n = parseFloat(limpiarNumero(valor));
        return isNaN(n) ? 0 : n;
    }

    function formato(valor) {
        return Number(valor || 0).toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    function actualizarDesdeQuetzales() {
        const q = numero(qVisible.value);
        const tc = numero(tipoCambio.value);

        if (q <= 0 || tc <= 0) {
            qHidden.value = '';
            dHidden.value = '';
            montoHidden.value = '';
            dVisible.value = '';
            return;
        }

        const d = q / tc;

        qHidden.value = q.toFixed(2);
        dHidden.value = d.toFixed(2);
        montoHidden.value = q.toFixed(2);
        dVisible.value = formato(d);
    }

    function actualizarDesdeDolares() {
        const d = numero(dVisible.value);
        const tc = numero(tipoCambio.value);

        if (d <= 0 || tc <= 0) {
            qHidden.value = '';
            dHidden.value = '';
            montoHidden.value = '';
            qVisible.value = '';
            return;
        }

        const q = d * tc;

        dHidden.value = d.toFixed(2);
        qHidden.value = q.toFixed(2);
        montoHidden.value = q.toFixed(2);
        qVisible.value = formato(q);
    }

    qVisible.addEventListener('input', function () {
        ultimoEditado = 'quetzales';
        actualizarDesdeQuetzales();
    });

    dVisible.addEventListener('input', function () {
        ultimoEditado = 'dolares';
        actualizarDesdeDolares();
    });

    tipoCambio.addEventListener('input', function () {
        if (ultimoEditado === 'dolares') {
            actualizarDesdeDolares();
        } else {
            actualizarDesdeQuetzales();
        }
    });

    qVisible.addEventListener('blur', function () {
        if (qVisible.value) qVisible.value = formato(numero(qVisible.value));
    });

    dVisible.addEventListener('blur', function () {
        if (dVisible.value) dVisible.value = formato(numero(dVisible.value));
    });
});
</script>
