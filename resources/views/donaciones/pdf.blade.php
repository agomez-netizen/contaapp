<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Donación #{{ $donacion->id_donacion }}</title>
  <style>
    @page { margin: 18mm 14mm; }
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color:#111; }
    .header { display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:10px; }
    .title { font-size: 16px; font-weight: 700; }
    .meta { font-size: 11px; color:#666; text-align:right; }
    .section { margin-top: 12px; }
    .section h3 { margin: 0 0 6px; font-size: 13px; color:#0a7a3c; border-bottom: 1px solid #dcdcdc; padding-bottom:4px; }
    table { width:100%; border-collapse: collapse; }
    td { padding: 6px 8px; border: 1px solid #e3e3e3; vertical-align: top; }
    .label { width: 30%; font-weight: 700; background: #f7f7f7; }
    .footer { margin-top: 18px; font-size: 11px; color:#666; }
    .sign { margin-top: 18px; width:100%; }
    .sign td { height: 45px; }
  </style>
</head>
<body>

  <div class="header">
    <div class="title"></div>
    <div class="meta">
      Generado: {{ now()->format('Y-m-d H:i') }}
    </div>
  </div>

    <div>
        <h1>ACTA DE DONACIÓN</h1>

        <p>
            En Antigua Guatemala, con fecha
            <strong>
                {{ $donacion->fecha_despachada
                    ? \Carbon\Carbon::parse($donacion->fecha_despachada)->format('d/m/Y')
                    : '__________' }}
            </strong>,
            <strong>LA PARTE DONANTE {{ $donacion->empresa ?? '________________________' }}</strong>
            realiza de forma libre y voluntaria una donación a favor de
            <strong>LA PARTE DONATARIA Asociación Pro Obras Sociales</strong>
            del siguiente bien o recurso:
            <strong>{{ $donacion->descripcion ?? '________________________' }}</strong>.
        </p>

        <p>
            LA PARTE DONATARIA manifiesta su aceptación expresa de la presente donación.
        </p>

        <p>
            La donación se efectúa de manera gratuita y surte efectos a partir de la fecha
            de firma del presente documento, quedando las partes sujetas a la normativa
            vigente aplicable.
        </p>
    </div>


  <div class="section">
    <h3>Datos de la Donación</h3>
    <table>
      <tr><td class="label">Fecha despachada</td><td>{{ $donacion->fecha_despachada }}</td></tr>
      <tr><td class="label">Empresa</td><td>{{ $donacion->empresa }}</td></tr>
      <tr><td class="label">NIT</td><td>{{ $donacion->nit }}</td></tr>
      <tr><td class="label">Contacto</td><td>{{ $donacion->contacto }}</td></tr>
      <tr><td class="label">Teléfono</td><td>{{ $donacion->telefono }}</td></tr>
      <tr><td class="label">Correo</td><td>{{ $donacion->correo }}</td></tr>
      <tr><td class="label">Unidades</td><td>{{ $donacion->unidades }}</td></tr>
      <tr><td class="label">Descripción</td><td>{{ $donacion->descripcion }}</td></tr>
      <tr><td class="label">Valor total donación</td><td>{{ number_format((float)$donacion->valor_total_donacion, 2) }}</td></tr>
    </table>
  </div>

  <div class="section">
    <h3>Entrega y Recepción</h3>
    <table>
      <tr><td class="label">Ubicación</td><td>{{ $donacion->ubicacion ?? $donacion->id_ubicacion }}</td></tr>
      <tr><td class="label">Fecha que recibe</td><td>{{ $donacion->fecha_recibe }}</td></tr>
      <tr><td class="label">Quién recibe</td><td>{{ $donacion->quien_recibe }}</td></tr>
      <tr><td class="label">Tipo de donación</td><td>{{ $donacion->tipo_donacion ?? $donacion->id_tipo_donacion }}</td></tr>
      <tr><td class="label">Unidades entrega</td><td>{{ $donacion->unidades_entrega }}</td></tr>
      <tr><td class="label">Persona que gestionó</td><td>{{ $donacion->persona_gestiono }}</td></tr>
    </table>
  </div>


  <table class="sign">
    <tr>
      <td style="border:none; width:50%; padding-top:20px;">
        ___________________________<br>
        Entrega
      </td>
      <td style="border:none; width:50%; padding-top:20px;">
        ___________________________<br>
        Recibe
      </td>
    </tr>
  </table>

  <div class="footer">
    Documento generado automáticamente.
  </div>

</body>
</html>
