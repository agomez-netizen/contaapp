{{-- Coloca este botón junto a Editar y Volver en show.blade.php --}}

<a href="{{ route('cooperantes.ficha.pdf', $organizacion->id) }}"
   class="btn btn-danger">
    Exportar PDF
</a>
