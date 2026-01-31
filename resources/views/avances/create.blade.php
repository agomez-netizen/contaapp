{{-- resources/views/avances/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Registrar Avance')

@section('content')
<div class="container py-4">

  @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
  @endif

  @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      {{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
  @endif

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Registrar Avance</h4>
    <a class="btn btn-outline-secondary" href="{{ route('avances.byDate') }}">
      📅 Ver avances por fecha
    </a>
  </div>

  <div class="card shadow-sm border-0">
    <div class="card-body">
      <form method="POST" action="{{ route('avances.store') }}">
        @csrf

        <div class="row g-3 align-items-end">

          <div class="col-md-3">
            <label class="form-label">Fecha</label>
            <input type="date" name="fecha"
                   value="{{ old('fecha', date('Y-m-d')) }}"
                   class="form-control @error('fecha') is-invalid @enderror">
            @error('fecha') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-6">
            <label class="form-label">Proyecto</label>
            <select name="id_proyecto" class="form-select @error('id_proyecto') is-invalid @enderror">
              <option value="">— Seleccionar —</option>
              @foreach($proyectos as $p)
                <option value="{{ $p->id_proyecto }}" @selected(old('id_proyecto') == $p->id_proyecto)>
                  {{ $p->nombre }}
                </option>
              @endforeach
            </select>
            @error('id_proyecto') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-3 text-md-end">
            <button class="btn btn-success px-4" type="submit">
              ➕ Agregar
            </button>
          </div>

          <div class="col-12">
            <label class="form-label">Descripción</label>

            {{--<textarea id="descripcion" name="descripcion" rows="8"
              class="form-control @error('descripcion') is-invalid @enderror"
              placeholder="Escribe el avance...">{{ old('descripcion') }}</textarea>

            @error('descripcion') <div class="invalid-feedback">{{ $message }}</div> @enderror
            --}}

            <textarea name="descripcion" rows="4"
                      class="form-control @error('descripcion') is-invalid @enderror"
                      placeholder="Escribe el avance...">{{ old('descripcion') }}</textarea>
            @error('descripcion') <div class="invalid-feedback">{{ $message }}</div> @enderror


            <div class="form-text">
              .
            </div>
          </div>

        </div>
      </form>
    </div>
  </div>

</div>
@endsection

@push('scripts')
  {{-- TinyMCE con tu API Key --}}
  <script src="https://cdn.tiny.cloud/1/687zw6kzwgqgwr2oqdot47bz1hiy7k2bndnxr058jvd73m9g/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

  <script>
    tinymce.init({
      selector: '#descripcion',
      height: 320,
      menubar: false,

      plugins: 'link image lists table code autoresize',
      toolbar: 'undo redo | bold italic underline | bullist numlist | link image | table | code',

      // links
      link_default_target: '_blank',

      // imágenes (sube a tu servidor)
      automatic_uploads: true,
      images_upload_url: "{{ route('avances.uploadImage') }}",

      // handler para mandar CSRF y que Laravel no se ponga exquisito
      images_upload_handler: function (blobInfo, progress) {
        return new Promise(function (resolve, reject) {
          const xhr = new XMLHttpRequest();
          xhr.open('POST', "{{ route('avances.uploadImage') }}");

          xhr.onload = function() {
            if (xhr.status < 200 || xhr.status >= 300) {
              reject('Error HTTP: ' + xhr.status);
              return;
            }

            let json;
            try {
              json = JSON.parse(xhr.responseText);
            } catch (e) {
              reject('Respuesta inválida del servidor');
              return;
            }

            if (!json.location) {
              reject('El servidor no devolvió location');
              return;
            }

            resolve(json.location);
          };

          xhr.onerror = function () {
            reject('Error de red al subir la imagen');
          };

          const formData = new FormData();
          formData.append('_token', "{{ csrf_token() }}");
          formData.append('file', blobInfo.blob(), blobInfo.filename());

          xhr.send(formData);
        });
      }
    });
  </script>
@endpush
