<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
    h2 { margin: 0 0 8px 0; }
    .muted { color: #666; font-size: 10px; margin-bottom: 8px; }
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #ddd; padding: 6px; }
    th { background: #f2f2f2; text-align: left; }
    .right { text-align: right; }
  </style>
</head>
<body>
  <h2>Reporte de Donaciones</h2>
  <div class="muted">
    Generado: {{ now()->format('d/m/Y H:i') }}
  </div>

  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Fecha</th>
        <th>Empresa</th>
        <th>NIT</th>
        <th>Tipo</th>
        <th>Ubicación</th>
        <th>Proyecto</th>
        <th class="right">Valor</th>
        <th class="right">Impacto</th>
        <th>Registró</th>
      </tr>
    </thead>
    <tbody>
      @foreach($donaciones as $d)
      <tr>
        <td>{{ $d->id_donacion }}</td>
        <td>{{ $d->fecha_despachada ?: '-' }}</td>
        <td>{{ $d->empresa ?: '-' }}</td>
        <td>{{ $d->nit ?: '-' }}</td>
        <td>{{ $d->tipo_donacion ?: '-' }}</td>
        <td>{{ $d->ubicacion ?: '-' }}</td>
        <td>{{ $d->proyecto ?: '-' }}</td>
        <td class="right">Q{{ $d->valor_total_donacion !== null ? number_format((float)$d->valor_total_donacion, 2) : '-' }}</td>
        <td class="right">{{ $d->impacto_personas ?? '-' }}</td>
        <td>{{ $d->usuario ?: '-' }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</body>
</html>
