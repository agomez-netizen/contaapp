@if(isset($documentos) && $documentos->isNotEmpty())
  <div class="col-12 mt-3">
    <h6 class="text-primary mb-2">Documentos adjuntos</h6>
    <hr class="mt-0">
    @foreach(['osshp' => 'OSSHP', 'sat' => 'SAT'] as $tipoDocumento => $tituloDocumento)
      @if($documentos->where('tipo', $tipoDocumento)->isNotEmpty())
        <h6>{{ $tituloDocumento }}</h6>
        <div class="row g-3 mb-3">
          @foreach($documentos->where('tipo', $tipoDocumento) as $documento)
            <div class="col-sm-6 col-lg-4">
              <a class="card h-100 text-decoration-none" target="_blank" rel="noopener"
                 href="{{ route('donaciones.show', [$donacion->id_donacion, 'documento' => $documento->id_documento]) }}">
                @if(in_array($documento->mime, ['image/jpeg', 'image/png', 'image/webp'], true))
                  <img src="{{ route('donaciones.show', [$donacion->id_donacion, 'documento' => $documento->id_documento]) }}"
                       alt="{{ $documento->nombre }}" loading="lazy" style="width:100%;height:160px;object-fit:contain;background:#f6f8fa">
                @else
                  <div class="text-center py-4 bg-light">Documento PDF</div>
                @endif
                <div class="card-body">
                  <div style="overflow-wrap:anywhere">{{ $documento->nombre }}</div>
                  <small class="text-muted">{{ number_format($documento->tamano / 1024, 0) }} KB · Abrir documento</small>
                </div>
              </a>
            </div>
          @endforeach
        </div>
      @endif
    @endforeach
  </div>
@endif
