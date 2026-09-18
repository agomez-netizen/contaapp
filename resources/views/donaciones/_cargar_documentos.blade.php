<div class="mt-3">
  <label for="documentos_{{ $tipoDocumento }}" class="form-label">Adjuntar documentos {{ strtoupper($tipoDocumento) }}</label>
  <input id="documentos_{{ $tipoDocumento }}" name="documentos_{{ $tipoDocumento }}[]"
         type="file" multiple accept=".jpg,.jpeg,.png,.webp,.pdf" class="form-control">
  <div class="form-text">Fotos JPG, PNG, WEBP o PDF. Máximo 5 archivos por guardado, de hasta 10 MB cada uno. Los nuevos se agregan a los existentes.</div>
  @if($errors->any())
    <div class="form-text">Si seleccionó archivos, vuelva a seleccionarlos antes de guardar.</div>
  @endif
  @if(isset($documentos) && $documentos->where('tipo', $tipoDocumento)->isNotEmpty())
    <ul class="mt-2 mb-0">
      @foreach($documentos->where('tipo', $tipoDocumento) as $documento)
        <li><a href="{{ route('donaciones.show', [$donacion->id_donacion, 'documento' => $documento->id_documento]) }}" target="_blank" rel="noopener">{{ $documento->nombre }}</a></li>
      @endforeach
    </ul>
  @endif
</div>
