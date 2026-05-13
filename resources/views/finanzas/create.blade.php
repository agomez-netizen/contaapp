@extends('layouts.app')

@section('content')

<div class="container">

    <h4>Nuevo Movimiento Financiero</h4>

    <form action="{{ route('finanzas.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        @include('finanzas.form')

        <button type="submit" class="btn btn-primary">
            Guardar
        </button>

        <a href="{{ route('finanzas.index') }}" class="btn btn-secondary">
            Cancelar
        </a>
    </form>

</div>

@endsection
