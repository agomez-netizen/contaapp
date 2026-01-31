@csrf

<div class="row g-3">
  <div class="col-md-6">
    <label class="form-label">Nombre *</label>
    <input name="nombre" class="form-control @error('nombre') is-invalid @enderror"
           value="{{ old('nombre', $usuario->nombre ?? '') }}" required>
    @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label">Apellido *</label>
    <input name="apellido" class="form-control @error('apellido') is-invalid @enderror"
           value="{{ old('apellido', $usuario->apellido ?? '') }}" required>
    @error('apellido') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label">Usuario *</label>
    <input name="usuario" class="form-control @error('usuario') is-invalid @enderror"
           value="{{ old('usuario', $usuario->usuario ?? '') }}" required>
    @error('usuario') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label">
      Contraseña {{ isset($usuario) ? '(deja vacío para no cambiar)' : '*' }}
    </label>
    <input type="password" name="pass" class="form-control @error('pass') is-invalid @enderror"
           {{ isset($usuario) ? '' : 'required' }}>
    @error('pass') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label">Rol *</label>
    <select name="id_rol" class="form-select @error('id_rol') is-invalid @enderror" required>
      <option value="">Seleccione...</option>
      @foreach($roles as $r)
        <option value="{{ $r->id_rol }}"
          {{ (string)old('id_rol', $usuario->id_rol ?? '') === (string)$r->id_rol ? 'selected' : '' }}>
          {{ $r->nombre }}
        </option>
      @endforeach
    </select>
    @error('id_rol') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label">Estado *</label>
    <select name="estado" class="form-select @error('estado') is-invalid @enderror" required>
      <option value="1" {{ old('estado', $usuario->estado ?? 1) == 1 ? 'selected' : '' }}>Activo</option>
      <option value="0" {{ old('estado', $usuario->estado ?? 1) == 0 ? 'selected' : '' }}>Inactivo</option>
    </select>
    @error('estado') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>
</div>
