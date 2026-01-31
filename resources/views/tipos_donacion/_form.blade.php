@csrf

<div class="mb-3">
  <label class="form-label">Nombre *</label>
  <input type="text" name="nombre"
         class="form-control @error('nombre') is-invalid @enderror"
         value="{{ old('nombre', $tipo->nombre ?? '') }}"
         maxlength="120" required>
  @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
  <label class="form-label">Descripción</label>
  <textarea name="descripcion" rows="4"
    class="form-control @error('descripcion') is-invalid @enderror"
    maxlength="2000">{{ old('descripcion', $tipo->descripcion ?? '') }}</textarea>
  @error('descripcion') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="form-check mb-3">
  <input class="form-check-input" type="checkbox" name="activo" value="1" id="activo"
         {{ old('activo', ($tipo->activo ?? true)) ? 'checked' : '' }}>
  <label class="form-check-label" for="activo">Activo</label>
</div>
