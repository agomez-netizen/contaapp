<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resumen de Donaciones</title>

    <style>
        @page {
            margin: 24px 28px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #1f2937;
        }

        h1 {
            margin: 0 0 4px;
            font-size: 20px;
        }

        h2 {
            margin: 16px 0 8px;
            font-size: 14px;
        }

        .fecha {
            margin-bottom: 14px;
            color: #6b7280;
        }

        .cards,
        .graficas {
            width: 100%;
            border-collapse: separate;
            border-spacing: 8px;
        }

        .card {
            width: 33.33%;
            padding: 10px;
            text-align: center;
            border: 1px solid #d1d5db;
        }

        .card-label {
            margin-bottom: 4px;
            color: #6b7280;
            font-size: 9px;
        }

        .card-value {
            font-size: 16px;
            font-weight: bold;
        }

        .grafica-celda {
            width: 50%;
            padding: 8px;
            vertical-align: top;
            border: 1px solid #d1d5db;
        }

        .grafica-titulo {
            margin-bottom: 7px;
            font-size: 12px;
            font-weight: bold;
        }

        .grafica-img {
            display: block;
            width: 100%;
            max-height: 245px;
        }

        .tabla-resumen {
            width: 100%;
            margin-top: 8px;
            border-collapse: collapse;
        }

        .tabla-resumen th,
        .tabla-resumen td {
            padding: 6px;
            border: 1px solid #d1d5db;
        }

        .tabla-resumen th {
            background: #f3f4f6;
            text-align: left;
        }

        .texto-derecha {
            text-align: right;
        }

        .total {
            background: #e5e7eb;
            font-weight: bold;
        }

        .salto-pagina {
            page-break-before: always;
        }

        .grafica-avances {
            display: block;
            width: 100%;
            max-height: 470px;
        }
    </style>
</head>
<body>

<h1>Resumen de Donaciones</h1>

<div class="fecha">
    Generado: {{ now()->format('d/m/Y H:i') }}

    @if($from || $to)
        <br>
        Periodo:
        {{ $from ? \Carbon\Carbon::parse($from)->format('d/m/Y') : 'Inicio' }}
        al
        {{ $to ? \Carbon\Carbon::parse($to)->format('d/m/Y') : 'Actualidad' }}
    @endif
</div>

<table class="cards">
    <tr>
        <td class="card">
            <div class="card-label">Donaciones registradas</div>
            <div class="card-value">
                {{ number_format((int)($stats->total_donaciones ?? 0)) }}
            </div>
        </td>

        <td class="card">
            <div class="card-label">Valor total</div>
            <div class="card-value">
                Q {{ number_format((float)($stats->total_dinero ?? 0), 2) }}
            </div>
        </td>

        <td class="card">
            <div class="card-label">Impacto en personas</div>
            <div class="card-value">
                {{ number_format((int)($stats->total_impacto ?? 0)) }}
            </div>
        </td>
    </tr>
</table>



<h2>Resumen por Tipo de Donación</h2>

<table class="tabla-resumen">
    <thead>
        <tr>
            <th>Tipo de Donación</th>
            <th class="texto-derecha">Total (Q)</th>
        </tr>
    </thead>

    <tbody>
        @forelse($resumenTipos as $row)
            <tr>
                <td>{{ $row->tipo }}</td>
                <td class="texto-derecha">
                    Q {{ number_format((float)$row->total, 2) }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="2">
                    No hay datos para los filtros aplicados.
                </td>
            </tr>
        @endforelse
    </tbody>

    <tfoot>
        <tr class="total">
            <td>TOTAL</td>
            <td class="texto-derecha">
                Q {{ number_format((float)$totalGeneralTipos, 2) }}
            </td>
        </tr>
    </tfoot>
</table>

@if($graficaAvances)
    <div class="salto-pagina"></div>

    <h2>Porcentaje de Avances por Proyecto</h2>

    <img src="{{ $graficaAvances }}" class="grafica-avances">
@endif

</body>
</html>
