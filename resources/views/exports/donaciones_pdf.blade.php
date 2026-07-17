<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #222;
        }

        h2 {
            margin: 0 0 8px 0;
        }

        .muted {
            color: #666;
            font-size: 10px;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 6px;
        }

        th {
            background: #f2f2f2;
            text-align: left;
        }

        .right {
            text-align: right;
        }

        .center {
            text-align: center;
        }

        .total-row td {
            background: #e9ecef;
            font-weight: bold;
            border-top: 2px solid #333;
        }

        .summary-table {
            width: 45%;
            margin-top: 15px;
            margin-left: auto;
        }

        .summary-table td {
            padding: 8px;
        }

        .summary-label {
            background: #f2f2f2;
            font-weight: bold;
        }

        .summary-value {
            text-align: right;
            font-weight: bold;
        }

         @page {
        margin: 18px 20px;
    }

    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 8px;
        color: #111827;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }

    th {
        background-color: #eeeeee;
        font-weight: bold;
        text-align: left;
        padding: 5px 4px;
        border: 1px solid #d1d5db;
        vertical-align: middle;
    }

    td {
        padding: 4px;
        border: 1px solid #d1d5db;
        vertical-align: top;
        word-wrap: break-word;
        overflow-wrap: break-word;
    }

    tr {
        page-break-inside: avoid;
    }
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
            <th style="width: 7%;">Fecha</th>
            <th style="width: 13%;">Empresa</th>
            <th style="width: 7%;">NIT</th>
            <th style="width: 8%;">Tipo</th>
            <th style="width: 18%;">Descripción</th>
            <th style="width: 7%;">Ubicación</th>
            <th style="width: 10%;">Proyecto</th>
            <th style="width: 8%; text-align: right;">Valor</th>
            <th style="width: 6%; text-align: right;">Impacto</th>

            <th style="width: 8%;">Persona gestionó</th>
        </tr>
    </thead>

    <tbody>
        @foreach($donaciones as $donacion)
            <tr>
                <td>
                    {{ \Carbon\Carbon::parse($donacion->fecha_despachada)->format('d/m/Y') }}
                </td>

                <td>
                    {{ $donacion->empresa ?? '-' }}
                </td>

                <td>
                    {{ $donacion->nit ?? '-' }}
                </td>

                <td>
                    {{ $donacion->tipo_donacion ?? '-' }}
                </td>

                <td>
                    {{ $donacion->descripcion ?? '-' }}
                </td>

                <td>
                    {{ $donacion->ubicacion ?? '-' }}
                </td>

                <td>
                    {{ $donacion->proyecto ?? '-' }}
                </td>

                <td style="text-align: right; white-space: nowrap;">
                    Q{{ number_format((float) $donacion->valor_total_donacion, 2) }}
                </td>

                <td style="text-align: right;">
                    {{ number_format((int) ($donacion->impacto_personas ?? 0)) }}
                </td>

               

                <td>
                    {{ $donacion->persona_gestiono ?? '-' }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

    <table class="summary-table">
        <tr>
            <td class="summary-label">
                Cantidad de donaciones
            </td>

            <td class="summary-value">
                {{ number_format($donaciones->count()) }}
            </td>
        </tr>

        <tr>
            <td class="summary-label">
                Valor total de donaciones
            </td>

            <td class="summary-value">
                Q{{ number_format((float) ($totalGeneral ?? 0), 2) }}
            </td>
        </tr>

        <tr>
            <td class="summary-label">
                Impacto total
            </td>

            <td class="summary-value">
                {{ number_format((int) ($totalImpacto ?? 0)) }}
            </td>
        </tr>
    </table>

</body>
</html>
