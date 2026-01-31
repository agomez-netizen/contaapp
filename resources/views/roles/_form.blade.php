@csrf

<div class="mb-3">
  <label class="form-label">Nombre *</label>
  <input name="nombre" class="form-control @error('nombre') is-invalid @enderror"
         value="{{ old('nombre', $rol->nombre ?? '') }}" required maxlength="120">
  @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
  <label class="form-label">Descripción</label>
  <textarea name="descripcion" rows="4"
    class="form-control @error('descripcion') is-invalid @enderror"
    maxlength="2000">{{ old('descripcion', $rol->descripcion ?? '') }}</textarea>
  @error('descripcion') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
