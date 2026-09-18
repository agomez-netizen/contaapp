@php
    $filasIniciales = [];
    if (isset($donacion)) {
        $filasIniciales = $donacion->detalles->map(function ($detalle) {
            return $detalle->only(['id_tipo_donacion', 'id_proyecto', 'descripcion', 'unidades', 'monto']);
        })->all();
        if (!$filasIniciales) {
            $filasIniciales = [[
                'id_tipo_donacion' => $donacion->id_tipo_donacion,
                'id_proyecto' => $donacion->id_proyecto,
                'descripcion' => $donacion->descripcion,
                'unidades' => $donacion->unidades,
                'monto' => $donacion->valor_total_donacion ?? '0.00',
            ]];
        }
    }
    $filasItems = old('items', $filasIniciales ?: [['id_tipo_donacion' => '', 'id_proyecto' => '', 'descripcion' => '', 'unidades' => '', 'monto' => '']]);
    $filasItems = is_array($filasItems) ? array_values($filasItems) : [];
@endphp
<hr class="my-4">
<style>
#detalle-donacion #titulo-items,
#detalle-donacion #agregar-item {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 8px 12px;
    border: 1px solid #b9e3cf;
    border-radius: 999px;
    background: #eaf7f0;
    color: #00864b;
    font-size: 16px;
    font-weight: 700;
    line-height: 1.2;
    text-decoration: none;
    margin: 0;
}
#detalle-donacion #agregar-item {
    cursor: pointer;
    transition: background-color .15s ease, border-color .15s ease;
}
#detalle-donacion #agregar-item:hover:not(:disabled) {
    background: #d9f0e3;
    border-color: #86cba7;
}
#detalle-donacion #agregar-item:focus-visible {
    outline: 3px solid #86cba7;
    outline-offset: 2px;
}
#detalle-donacion #agregar-item:disabled {
    opacity: .55;
    cursor: not-allowed;
}
</style>
<section id="detalle-donacion" aria-labelledby="titulo-items">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
        <h6 id="titulo-items" class="mb-0">Ítems del recibo</h6>
        <button type="button" id="agregar-item" class="btn btn-sm">+ Agregar ítem</button>
    </div>
    <p class="text-muted small">Seleccione el tipo y el proyecto de cada ítem e ingrese su monto total en quetzales, no el precio por unidad. Puede distribuir un mismo recibo entre varios proyectos. Para aportes monetarios, las unidades pueden quedar vacías.</p>
    <div class="table-responsive">
        <table class="table align-middle" style="min-width:1050px">
            <thead><tr><th style="width:19%">Tipo de donación</th><th style="width:20%">Proyecto</th><th>Descripción</th><th style="width:10%">Unidades</th><th style="width:16%">Monto total (Q)</th><th>Acción</th></tr></thead>
            <tbody id="filas-items">
                @foreach($filasItems as $indice => $fila)
                    <tr>
                        <td><select data-campo="id_tipo_donacion" name="items[{{ $indice }}][id_tipo_donacion]" class="form-select" aria-label="Tipo de donación" required>
                            <option value="">Seleccione...</option>
                            @foreach($tipos as $t)
                                <option value="{{ $t->id_tipo_donacion }}" {{ (string) ($fila['id_tipo_donacion'] ?? '') === (string) $t->id_tipo_donacion ? 'selected' : '' }}>{{ $t->nombre }}</option>
                            @endforeach
                        </select></td>
                        <td><select data-campo="id_proyecto" name="items[{{ $indice }}][id_proyecto]" class="form-select" aria-label="Proyecto del ítem" required>
                            <option value="">Seleccione...</option>
                            @foreach($proyectos as $p)
                                <option value="{{ $p->id_proyecto }}" {{ (string) ($fila['id_proyecto'] ?? '') === (string) $p->id_proyecto ? 'selected' : '' }}>{{ $p->nombre }}</option>
                            @endforeach
                        </select></td>
                        <td><input data-campo="descripcion" name="items[{{ $indice }}][descripcion]" class="form-control" aria-label="Descripción del ítem" maxlength="5000" value="{{ $fila['descripcion'] ?? '' }}"></td>
                        <td><input data-campo="unidades" name="items[{{ $indice }}][unidades]" type="number" min="0" max="4294967295" step="1" class="form-control" aria-label="Unidades del ítem" value="{{ $fila['unidades'] ?? '' }}"></td>
                        <td><input data-campo="monto" name="items[{{ $indice }}][monto]" type="number" min="0" max="9999999999.99" step="0.01" class="form-control" aria-label="Monto total del ítem" value="{{ $fila['monto'] ?? '' }}" required></td>
                        <td><button type="button" class="btn btn-outline-danger btn-sm quitar-item" aria-label="Quitar ítem">Quitar</button></td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot><tr><td colspan="4" class="text-end">Total del recibo</td><td colspan="2" id="total-items" aria-live="polite">Q 0.00</td></tr></tfoot>
        </table>
    </div>
    <p id="limite-items" class="text-danger small" aria-live="polite"></p>
    <noscript><p class="text-danger">Active JavaScript para agregar filas y visualizar el total. El servidor valida y calcula los importes al guardar.</p></noscript>
</section>
<template id="plantilla-item">
    <tr>
        <td><select data-campo="id_tipo_donacion" class="form-select" aria-label="Tipo de donación" required>
            <option value="">Seleccione...</option>
            @foreach($tipos as $t)<option value="{{ $t->id_tipo_donacion }}">{{ $t->nombre }}</option>@endforeach
        </select></td>
        <td><select data-campo="id_proyecto" class="form-select" aria-label="Proyecto del ítem" required>
            <option value="">Seleccione...</option>
            @foreach($proyectos as $p)<option value="{{ $p->id_proyecto }}">{{ $p->nombre }}</option>@endforeach
        </select></td>
        <td><input data-campo="descripcion" class="form-control" aria-label="Descripción del ítem" maxlength="5000"></td>
        <td><input data-campo="unidades" type="number" min="0" max="4294967295" step="1" class="form-control" aria-label="Unidades del ítem"></td>
        <td><input data-campo="monto" type="number" min="0" max="9999999999.99" step="0.01" class="form-control" aria-label="Monto total del ítem" required></td>
        <td><button type="button" class="btn btn-outline-danger btn-sm quitar-item" aria-label="Quitar ítem">Quitar</button></td>
    </tr>
</template>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const cuerpo = document.getElementById('filas-items');
    const agregar = document.getElementById('agregar-item');
    const formulario = cuerpo.closest('form');
    function recalcular() {
        let centavos = 0;
        let unidades = 0;
        let hayUnidades = false;
        Array.from(cuerpo.rows).forEach((fila, indice) => {
            fila.querySelectorAll('[data-campo]').forEach(campo => {
                campo.name = `items[${indice}][${campo.dataset.campo}]`;
            });
            const monto = fila.querySelector('[data-campo="monto"]').value;
            if (/^\d+(\.\d{1,2})?$/.test(monto)) {
                const partes = monto.split('.');
                centavos += Number(partes[0]) * 100 + Number((partes[1] || '').padEnd(2, '0'));
            }
            const cantidad = fila.querySelector('[data-campo="unidades"]').value;
            if (cantidad !== '') { hayUnidades = true; unidades += Number(cantidad) || 0; }
            fila.querySelector('.quitar-item').disabled = cuerpo.rows.length === 1;
        });
        const total = (centavos / 100).toFixed(2);
        const texto = 'Q ' + (centavos / 100).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        document.getElementById('total-items').textContent = texto;
        formulario.querySelector('[name="valor_total_donacion"]').value = total;
        const display = document.getElementById('valor_total_donacion_display');
        if (display) display.value = texto;
        formulario.querySelector('[name="unidades"]').value = hayUnidades ? unidades : '';
        const unidadesEntrega = formulario.querySelector('[name="unidades_entrega"]');
        if (unidadesEntrega) unidadesEntrega.value = hayUnidades ? unidades : '';
        agregar.disabled = cuerpo.rows.length >= 100;
        document.getElementById('limite-items').textContent = agregar.disabled ? 'Máximo: 100 ítems por recibo.' : '';
    }
    agregar.addEventListener('click', function () {
        if (cuerpo.rows.length >= 100) return;
        cuerpo.appendChild(document.getElementById('plantilla-item').content.cloneNode(true));
        recalcular();
        cuerpo.lastElementChild.querySelector('select').focus();
    });
    cuerpo.addEventListener('click', function (event) {
        const boton = event.target.closest('.quitar-item');
        if (boton && cuerpo.rows.length > 1) { boton.closest('tr').remove(); recalcular(); }
    });
    cuerpo.addEventListener('input', recalcular);
    cuerpo.addEventListener('change', recalcular);
    formulario.addEventListener('submit', recalcular);
    recalcular();
});
</script>
