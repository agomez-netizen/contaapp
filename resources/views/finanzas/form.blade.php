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
            <option value="FACTURA">Factura</option>
            <option value="RECIBO">Recibo</option>
            <option value="COTIZACION">Cotización</option>
            <option value="OTRO">Otro</option>
        </select>
    </div>

    <div class="col-md-4">
        <label class="form-label">No. Documento</label>
        <input type="text"
               name="no_documento"
               class="form-control"
               placeholder="Ingrese número">
    </div>

    <div class="col-md-4">
        <label class="form-label">Fecha Documento</label>
        <input type="date"
               name="fecha_documento"
               class="form-control"
               required>
    </div>

    <div class="col-md-4">
        <label class="form-label">Proyecto</label>
        <select name="id_proyecto"
                id="id_proyecto"
                class="form-select"
                required>
            <option value="">Seleccione</option>

            @foreach($proyectos as $proyecto)
                <option value="{{ $proyecto->id_proyecto }}">
                    {{ $proyecto->nombre }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <label class="form-label">Subproyecto</label>
        <select name="id_subproyecto"
                id="id_subproyecto"
                class="form-select">
            <option value="">Seleccione proyecto primero</option>
        </select>
    </div>

    <div class="col-md-4">
        <label class="form-label">Rubro</label>
        <select name="id_rubro" class="form-select">
            <option value="">Seleccione</option>

            @foreach($rubros as $rubro)
                <option value="{{ $rubro->id_rubro }}">
                    {{ $rubro->nombre }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <label class="form-label">Empresa</label>
        <input type="text"
               name="empresa"
               class="form-control"
               placeholder="Nombre empresa">
    </div>

    <div class="col-md-4">
        <label class="form-label">Proveedor</label>
        <input type="text"
               name="proveedor"
               class="form-control"
               placeholder="Nombre proveedor">
    </div>

    <div class="col-md-4">
        <label class="form-label">Monto</label>
        <input type="number"
               step="0.01"
               name="monto"
               class="form-control"
               placeholder="0.00"
               required>
    </div>

    <div class="col-md-12">
        <label class="form-label">Descripción</label>
        <textarea name="descripcion"
                  class="form-control"
                  rows="4"
                  placeholder="Ingrese descripción"></textarea>
    </div>

    <div class="col-md-6">
        <label class="form-label">Cargar Documento</label>
        <input type="file"
               name="archivo"
               class="form-control">
    </div>

    <div class="col-md-12 mt-3">
        <button type="submit" class="btn btn-primary">
            Guardar Movimiento
        </button>
    </div>

</div>
