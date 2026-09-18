@if(isset($detalles) && $detalles->isNotEmpty())
<div class="section col-12 mt-3">
  <h3 style="font-size:14px">Ítems del recibo</h3>
  <div style="overflow-x:auto">
    <table class="table table-bordered" style="width:100%; border-collapse:collapse; font-size:12px">
      <thead><tr>
        <th style="border:1px solid #ddd; padding:6px">Tipo de donación</th>
        <th style="border:1px solid #ddd; padding:6px">Proyecto</th>
        <th style="border:1px solid #ddd; padding:6px">Descripción</th>
        <th style="border:1px solid #ddd; padding:6px">Unidades</th>
        <th style="border:1px solid #ddd; padding:6px">Monto total (Q)</th>
      </tr></thead>
      <tbody>
        @foreach($detalles as $detalle)
          <tr>
            <td style="border:1px solid #ddd; padding:6px">{{ $detalle->tipo_donacion }}</td>
            <td style="border:1px solid #ddd; padding:6px">{{ $detalle->proyecto }}</td>
            <td style="border:1px solid #ddd; padding:6px; overflow-wrap:break-word">{{ $detalle->descripcion ?? '—' }}</td>
            <td style="border:1px solid #ddd; padding:6px; text-align:right">{{ $detalle->unidades ?? '—' }}</td>
            <td style="border:1px solid #ddd; padding:6px; text-align:right">Q {{ number_format((float) $detalle->monto, 2) }}</td>
          </tr>
        @endforeach
      </tbody>
      <tfoot><tr>
        <td colspan="4" style="border:1px solid #ddd; padding:6px; text-align:right">Total del recibo</td>
        <td style="border:1px solid #ddd; padding:6px; text-align:right">Q {{ number_format((float) $donacion->valor_total_donacion, 2) }}</td>
      </tr></tfoot>
    </table>
  </div>
</div>
@endif
