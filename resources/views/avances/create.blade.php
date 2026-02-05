@extends('layouts.app')

@section('title', 'Registrar Avance')

@section('content')
<div class="container py-4">

  @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show">
      {{ session('success') }}
      <button class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show">
      <strong>Corrige los errores:</strong>
      <ul class="mb-0">
        @foreach ($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
      <button class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Registrar Avance</h4>
    <a href="{{ route('avances.byDate') }}" class="btn btn-outline-secondary">
      📅 Ver avances por fecha
    </a>
  </div>

  <div class="card shadow-sm border-0">
    <div class="card-body">

      <form id="form-avance" method="POST" action="{{ route('avances.store') }}">
        @csrf

        <div class="mb-3">
          <label class="form-label">Proyecto</label>
          <select name="id_proyecto" class="form-select" required>
            <option value="">— Seleccionar —</option>
            @foreach ($proyectos as $p)
              <option value="{{ $p->id_proyecto }}"
                {{ old('id_proyecto') == $p->id_proyecto ? 'selected' : '' }}>
                {{ $p->nombre }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Descripción</label>

          <textarea
            name="descripcion"
            id="descripcion"
            class="form-control"
            rows="6"
          >{{ old('descripcion') }}</textarea>

          <small class="text-muted">
            Puedes usar negrita, listas y pegar enlaces.
          </small>
        </div>

        <button type="submit" class="btn btn-primary">
          + Agregar
        </button>
      </form>

    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.tiny.cloud/1/687zw6kzwgqgwr2oqdot47bz1hiy7k2bndnxr058jvd73m9g/tinymce/6/tinymce.min.js"
        referrerpolicy="origin"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

  tinymce.init({
    selector: '#descripcion',
    height: 260,
    menubar: false,
    branding: false,

    plugins: 'link lists autoresize',
    toolbar: 'undo redo | bold italic underline | bullist numlist | link',

    link_default_target: '_blank',
    link_assume_external_targets: true,

    // 🔒 solo HTML básico permitido
    valid_elements: 'p,br,strong/b,em/i,u,ul,ol,li,a[href|target|rel]',
    invalid_elements: 'script,iframe,style,object,embed',

  });

  // 🔑 sincroniza antes de enviar
  document.getElementById('form-avance')
    .addEventListener('submit', function () {
      tinymce.triggerSave();
    });

});
</script>
@endpush
