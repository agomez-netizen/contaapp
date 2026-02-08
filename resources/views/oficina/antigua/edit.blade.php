@extends('layouts.app')
@section('title','Editar Documento - Antigua')

@section('content')
<div class="container py-4">

  @if ($errors->any())
    <div class="alert alert-danger">
      <strong>Corrige los errores:</strong>
      <ul class="mb-0">
        @foreach($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">✏️ Editar Documento (Antigua)</h4>
    <a href="{{ route('oficina.antigua.index') }}" class="btn btn-outline-secondary">
      ← Volver
    </a>
  </div>

  <div class="card border-0 shadow-sm">
    <div class="card-body">
      <form method="post"
            action="{{ route('oficina.antigua.update', $row->id) }}"
            enctype="multipart/form-data">

        @csrf
        @method('PUT')

        @include('oficina.antigua._form', ['row' => $row])

        <div class="mt-3">
          <button class="btn btn-primary">Actualizar</button>
        </div>
      </form>
    </div>
  </div>

</div>
@endsection
