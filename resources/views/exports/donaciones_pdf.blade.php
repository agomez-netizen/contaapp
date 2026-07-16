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
            @forelse($donaciones as $d)
                <tr>
                    <td>{{ $d->id_donacion }}</td>

                    <td>
                        {{ $d->fecha_despachada
                            ? \Carbon\Carbon::parse($d->fecha_despachada)->format('d/m/Y')
                            : '-' }}
                    </td>

                    <td>{{ $d->empresa ?: '-' }}</td>

                    <td>{{ $d->nit ?: '-' }}</td>

                    <td>{{ $d->tipo_donacion ?: '-' }}</td>

                    <td>{{ $d->ubicacion ?: '-' }}</td>

                    <td>{{ $d->proyecto ?: '-' }}</td>

                    <td class="right">
                        Q{{ number_format((float) ($d->valor_total_donacion ?? 0), 2) }}
                    </td>

                    <td class="right">
                        {{ number_format((int) ($d->impacto_personas ?? 0)) }}
                    </td>

                    <td>{{ $d->usuario ?: '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="center">
                        No hay registros para mostrar.
                    </td>
                </tr>
            @endforelse
        </tbody>

        <tfoot>
            <tr class="total-row">
                <td colspan="7" class="right">
                    TOTAL GENERAL
                </td>

                <td class="right">
                    Q{{ number_format((float) ($totalGeneral ?? 0), 2) }}
                </td>

                <td class="right">
                    {{ number_format((int) ($totalImpacto ?? 0)) }}
                </td>

                <td></td>
            </tr>
        </tfoot>
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
