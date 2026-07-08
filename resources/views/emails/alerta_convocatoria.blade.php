<h2>Alerta de convocatoria</h2>

<p>La siguiente convocatoria vence en 7 días:</p>

<ul>
    <li><strong>Convocatoria:</strong> {{ $convocatoria->nombre }}</li>
    <li><strong>Fecha cierre:</strong> {{ $convocatoria->fecha_cierre }}</li>
    <li><strong>Monto máximo:</strong> {{ $convocatoria->moneda }} {{ number_format($convocatoria->monto_maximo, 2) }}</li>
    <li><strong>Estado:</strong> {{ $convocatoria->estado }}</li>
</ul>

<p>
    Revisar documentación, requisitos y enviar solicitud antes de la fecha límite.
</p>
