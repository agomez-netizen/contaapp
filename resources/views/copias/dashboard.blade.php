@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
@php
  // Blindaje total
  $donaciones = $donaciones ?? collect();
  $tipos = $tipos ?? collect();
  $proyectos = $proyectos ?? collect();
  $porTipo = $porTipo ?? collect();
  $porProyecto = $porProyecto ?? collect();

  $stats = $stats ?? (object)[
    'total_donaciones' => 0,
    'total_dinero' => 0,
    'total_impacto' => 0,
  ];

  $q = $q ?? '';
  $from = $from ?? '';
  $to = $to ?? '';
  $tipo = $tipo ?? '';
  $proyecto = $proyecto ?? '';
@endphp

<div class="container py-4">


  {{-- ===== HEADER LISTADO ===== --}}
  <div class="d-flex flex-wrap gap-2 align-items-center justify-content-between mb-3">
    <div>
      <h4 class="mb-1">Donaciones Registradas</h4>
      <div class="text-muted">Control Interno AAPOS</div>
    </div>

    <div class="d-flex gap-2">
      <a href="{{ route('donaciones.export.excel') }}" class="btn btn-outline-success">
        <i class="bi bi-file-earmark-excel me-2"></i>Exportar Excel
      </a>

      <a href="{{ route('donaciones.export.pdf') }}" class="btn btn-outline-danger">
        <i class="bi bi-file-earmark-pdf me-2"></i>Exportar PDF
      </a>

      <a href="{{ route('donaciones.create') }}" class="btn btn-success">
        + Nueva Donación
      </a>
    </div>
  </div>

  {{-- ===== ALERTAS ===== --}}
  @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
  @endif

  @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      {{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
  @endif

  <div class="card border-0 shadow-sm">
    <div class="card-body">

      {{-- FILTROS --}}
      <form class="row g-2 mb-3" method="GET" action="{{ route('dashboard') }}">
       {{-- <div class="col-md-4">
          <input type="text" name="q" value="{{ $q }}" class="form-control"
                 placeholder="Buscar por empresa, NIT, contacto, referencias...">
        </div>--}}

        <div class="col-md-2">
          <input type="date" name="from" value="{{ $from }}" class="form-control" title="Desde">
        </div>

        <div class="col-md-2">
          <input type="date" name="to" value="{{ $to }}" class="form-control" title="Hasta">
        </div>

        <div class="col-md-2">
          <select name="tipo" class="form-select">
            <option value="">Tipo (todos)</option>
            @foreach($tipos as $t)
              <option value="{{ $t->id_tipo_donacion }}" {{ (string)$tipo === (string)$t->id_tipo_donacion ? 'selected' : '' }}>
                {{ $t->nombre }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="col-md-2">
          <select name="proyecto" class="form-select">
            <option value="">Proyecto (todos)</option>
            @foreach($proyectos as $p)
              <option value="{{ $p->id_proyecto }}" {{ (string)$proyecto === (string)$p->id_proyecto ? 'selected' : '' }}>
                {{ $p->nombre }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="col-md-auto">
          <button class="btn btn-primary">Filtrar</button>
        </div>
        <div class="col-md-auto">
          <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Limpiar</a>
        </div>
      </form>

       {{-- <div class="table-responsive">
        <table class="table align-middle table-hover">
          <thead class="table-light">
            <tr>
              <th>#</th>
              <th>Fecha</th>
              <th>Empresa</th>
              <th>NIT</th>
              <th>Tipo</th>
              <th>Ubicación</th>
              <th>Proyecto</th>
              <th class="text-end">Valor</th>
              <th class="text-end">Impacto</th>
              <th>Registró</th>
              <th class="text-end">Acciones</th>
            </tr>
          </thead>

          <tbody>
            @forelse ($donaciones as $d)
              <tr onclick="window.location='{{ route('donaciones.show', $d->id_donacion) }}'" style="cursor:pointer">
                <td class="fw-bold">{{ $d->id_donacion }}</td>
                <td>{{ $d->fecha_despachada ? \Carbon\Carbon::parse($d->fecha_despachada)->format('d/m/Y') : '-' }}</td>
                <td>{{ $d->empresa ?? '-' }}</td>
                <td>{{ $d->nit ?? '-' }}</td>
                <td><span class="badge text-bg-primary">{{ $d->tipo_donacion ?? '—' }}</span></td>
                <td><span class="badge text-bg-success">{{ $d->ubicacion ?? '—' }}</span></td>
                <td>{{ $d->proyecto ?? '—' }}</td>

                <td class="text-end">
                  {{ $d->valor_total_donacion !== null ? number_format((float)$d->valor_total_donacion, 2) : '—' }}
                </td>
                <td class="text-end">{{ $d->impacto_personas ?? '—' }}</td>
                <td class="text-muted">{{ $d->usuario ?? '—' }}</td>

                <td class="text-end">
                  <a href="{{ route('donaciones.edit', $d->id_donacion) }}"
                     class="btn btn-outline-primary btn-sm"
                     title="Editar"
                     onclick="event.stopPropagation()">
                    <i class="bi bi-pencil-square"></i>
                  </a>

                  <form action="{{ route('donaciones.destroy', $d->id_donacion) }}"
                        method="POST"
                        class="d-inline"
                        onclick="event.stopPropagation()">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="btn btn-outline-danger btn-sm"
                            title="Eliminar"
                            onclick="event.stopPropagation(); return confirm('¿Eliminar este registro?')">
                      <i class="bi bi-trash"></i>
                    </button>
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="11" class="text-center text-muted py-4">
                  No hay registros aún.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>--}}

      {{-- PAGINACIÓN --}}
      {{-- <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
        <div class="text-muted small">
          @if(method_exists($donaciones, 'firstItem'))
            Mostrando {{ $donaciones->firstItem() ?? 0 }} – {{ $donaciones->lastItem() ?? 0 }}
            de {{ $donaciones->total() ?? 0 }} registros
          @else
            Mostrando 0 – 0 de 0 registros
          @endif
        </div>

        <div>
          @if(method_exists($donaciones, 'links'))
            {{ $donaciones->onEachSide(1)->links('pagination::bootstrap-5') }}
          @endif
        </div>
      </div>--}}

    </div>
  </div>
<br>
  {{-- ===== RESUMEN ===== --}}
  <div class="row g-3 mb-4">
    <div class="col-md-4">
      <div class="card border-0 shadow-sm">
        <div class="card-body d-flex align-items-center justify-content-between">
          <div>
            <div class="text-muted small">Donaciones</div>
            <div class="fs-4 fw-bold">{{ number_format((int)($stats->total_donaciones ?? 0)) }}</div>
          </div>
          <div class="badge text-bg-primary rounded-pill px-3 py-2">Registros</div>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card border-0 shadow-sm">
        <div class="card-body d-flex align-items-center justify-content-between">
          <div>
            <div class="text-muted small">Total (Q)</div>
            <div class="fs-4 fw-bold">{{ number_format((float)($stats->total_dinero ?? 0), 2) }}</div>
          </div>
          <div class="badge text-bg-success rounded-pill px-3 py-2">Dinero</div>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card border-0 shadow-sm">
        <div class="card-body d-flex align-items-center justify-content-between">
          <div>
            <div class="text-muted small">Impacto</div>
            <div class="fs-4 fw-bold">{{ number_format((int)($stats->total_impacto ?? 0)) }}</div>
          </div>
          <div class="badge text-bg-warning rounded-pill px-3 py-2">Personas</div>
        </div>
      </div>
    </div>
  </div>

  {{-- ===== GRÁFICAS ===== --}}
  <div class="row mb-4 g-3">
    <div class="col-md-6">
      <div class="card shadow-sm h-100">
        <div class="card-body">
          <h6 class="fw-semibold mb-3">💝 Donaciones por Tipo</h6>

          {{-- contenedor con alto controlado --}}
          <div style="height: 280px;">
            <canvas id="chartTipo"></canvas>
          </div>

          <div id="chartTipoEmpty" class="text-muted small mt-2 d-none">
            Sin datos para graficar con los filtros actuales.
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card shadow-sm h-100">
        <div class="card-body">
          <h6 class="fw-semibold mb-3">📌 Donaciones por Proyecto</h6>

          <div style="height: 280px;">
            <canvas id="chartProyecto"></canvas>
          </div>

          <div id="chartProyectoEmpty" class="text-muted small mt-2 d-none">
            Sin datos para graficar con los filtros actuales.
          </div>
        </div>
      </div>
    </div>
  </div>


{{-- ============================
  RESUMEN POR TIPO DE DONACIÓN
============================ --}}
<div class="card shadow-sm mb-4">
  <div class="card-body">
    <h5 class="fw-bold mb-3">📊 Resumen por Tipo de Donación</h5>

    <div class="table-responsive">
      <table class="table table-sm align-middle">
        <thead class="table-light">
          <tr>
            <th>Tipo de Donación</th>
            <th class="text-end">Total (Q)</th>
          </tr>
        </thead>

        <tbody>
          @foreach($resumenTipos as $row)
            <tr>
              <td>{{ $row->tipo }}</td>
              <td class="text-end fw-semibold">
                Q {{ number_format($row->total, 2) }}
              </td>
            </tr>
          @endforeach
        </tbody>

        <tfoot>
          <tr class="table-secondary">
            <th class="fw-bold">TOTAL</th>
            <th class="text-end fw-bold">
              Q {{ number_format($totalGeneralTipos, 2) }}
            </th>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
</div>

{{-- ===== GRÁFICA DE BARRAS POR PORCENTAJE ===== --}}

    <div>
      <h4 class="mb-1">Porcentaje de Avance por Proyecto</h4>
      <div class="text-muted">El porcentaje se calcula tomando la cantidad de avances registrados para un proyecto y dividiéndola entre el total de avances registrados en todos los proyectos del sistema. El resultado se multiplica por 100 para obtener el porcentaje.</div>
<div class="text-muted">
      Porcentaje de un proyecto =(Cantidad de avances del proyecto / Total de avances de todos los proyectos) × 100 </div>
    </div>
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-body">

                <h6 class="fw-semibold mb-3">
                    📊
                Avances por Proyecto </h6>

                <div style="height:600px;">
                    <canvas id="barChart"></canvas>
                </div>

                <div id="barChartEmpty" class="text-muted small mt-2 d-none">
                    Sin datos para graficar.
                </div>
            </div>
        </div>
    </div>
</div>



  {{-- SCATTER --}}
  <div class="mt-4" style="height:320px;">
    <canvas id="scatterChart" data-url="{{ route('dashboard.scatter') }}"></canvas>
  </div>

</div>

{{-- ===== SCRIPTS ===== --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
  const porTipoData = @json($porTipo);
  const porProyectoData = @json($porProyecto);

  function makeDoughnut(canvasId, emptyId, rows) {
    const el = document.getElementById(canvasId);
    const emptyEl = document.getElementById(emptyId);
    if (!el) return;

    const labels = (rows || []).map(r => r.label ?? 'Sin nombre');
    const values = (rows || []).map(r => Number(r.total ?? 0));

    const total = values.reduce((a,b)=>a+b,0);
    if (!labels.length || total <= 0) {
      if (emptyEl) emptyEl.classList.remove('d-none');
      return;
    }

    new Chart(el, {
      type: 'doughnut',
      data: {
        labels,
        datasets: [{ data: values }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '65%',
        plugins: {
          legend: { position: 'bottom' },
          tooltip: {
            callbacks: {
              label: function(ctx) {
                const sum = ctx.dataset.data.reduce((a,b)=>a+b,0);
                const value = Number(ctx.raw ?? 0);

                // Blindaje anti-NaN
                if (!sum || !isFinite(sum)) {
                  return `Q ${value.toLocaleString()}`;
                }

                const percent = ((value / sum) * 100);
                const pctText = isFinite(percent) ? percent.toFixed(1) : '0.0';
                return `Q ${value.toLocaleString()} (${pctText}%)`;
              }
            }
          }
        }
      }
    });
  }

  makeDoughnut('chartTipo', 'chartTipoEmpty', porTipoData);
  makeDoughnut('chartProyecto', 'chartProyectoEmpty', porProyectoData);

  // Nota: tu scatter ya lo manejas aparte (si tienes JS adicional, se queda igual)
// ===============================
// GRÁFICA DE BARRAS POR PROYECTO
// ===============================
const avancesPorProyectoData = @json($avancesPorProyecto);

const labelsBar = avancesPorProyectoData.map(item => item.label);
const valoresBar = avancesPorProyectoData.map(item => Number(item.total));

const totalBar = valoresBar.reduce((a, b) => a + b, 0);

const porcentajes = valoresBar.map(v =>
    totalBar > 0 ? Number(((v / totalBar) * 100).toFixed(2)) : 0
);

const ctx = document.getElementById('barChart');

if (ctx) {
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labelsBar,
            datasets: [{
                label: 'Porcentaje de avances',
                data: porcentajes,
                borderWidth: 1
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const i = context.dataIndex;
                            return `${porcentajes[i]}% (${valoresBar[i]} avances)`;
                        }
                    }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        callback: value => value + '%'
                    }
                },
                y: {
                    ticks: {
                        autoSkip: false
                    }
                }
            }
        }
    });
}


</script>
@endsection
