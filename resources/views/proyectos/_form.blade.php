{{-- resources/views/avances/_form.blade.php --}}

{{-- PROYECTO --}}
<div class="mb-3">
  <label class="form-label">Proyecto <span class="text-danger">*</span></label>

  <select name="id_proyecto"
          class="form-select @error('id_proyecto') is-invalid @enderror"
          required>
    <option value="">— Seleccionar —</option>
    @foreach($proyectos as $p)
      <option value="{{ $p->id_proyecto }}"
        {{ old('id_proyecto') == $p->id_proyecto ? 'selected' : '' }}>
        {{ $p->nombre }}
      </option>
    @endforeach
  </select>

  @error('id_proyecto')
    <div class="invalid-feedback">{{ $message }}</div>
  @enderror
</div>

{{-- DESCRIPCIÓN (TinyMCE) --}}
<div class="mb-3">
  <label class="form-label">Descripción <span class="text-danger">*</span></label>

  {{-- ID obligatorio para TinyMCE --}}
  <textarea id="descripcion"
            name="descripcion"
            class="form-control @error('descripcion') is-invalid @enderror"
            rows="6"
            required>{{ old('descripcion') }}</textarea>

  @error('descripcion')
    <div class="invalid-feedback">{{ $message }}</div>
  @enderror
</div>

{{-- BOTÓN --}}
<div class="text-end">
  <button type="submit" class="btn btn-primary">
    ✚ Agregar
  </button>
</div>
