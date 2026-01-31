@php
  $u = session('user'); // array guardado en sesión

  // Normalizamos rol desde sesión
  $rolName = strtoupper(trim($u['rol'] ?? $u['nombre_rol'] ?? ''));
  $rolId   = (int)($u['id_rol'] ?? 0);

  /*
    Roles reales en tu BD:
    1 = ADMIN
    2 = RIFA
    3 = DONACIONES (Gestor)
  */
  $isAdmin  = ($rolId === 1) || $rolName === 'ADMIN';
  $isRifa   = ($rolId === 2) || $rolName === 'RIFA';
  $isGestor = ($rolId === 3) || in_array($rolName, ['DONACIONES','GESTOR'], true);

  // SCOPE: evita IDs duplicados si este sidebar se imprime 2 veces (desktop + mobile)
  $scope = $scope ?? 'desk';

  // IDs únicos por menú
  $idDashboard     = "menuDashboard-{$scope}";
  $idDonaciones    = "menuDonaciones-{$scope}";
  $idPacientes     = "menuPacientes-{$scope}";
  $idMedios        = "menuMedios-{$scope}";
  $idProyectosAapos= "menuProyectosAapos-{$scope}";
  $idMantenimientos= "menuMantenimientos-{$scope}";

  // Para abrir submenús cuando estás dentro
  $donOpen   = request()->routeIs('donaciones.*');
  $pacOpen   = request()->routeIs('pacientes.*');
  $medOpen   = request()->routeIs('medios.*');
  $proyOpen  = request()->routeIs('proyectosaapos.*');

  // Mantenimientos (si estás dentro de cualquiera, se abre)
  $maintOpen = request()->routeIs('proyectos.*')
            || request()->routeIs('tipos_donacion.*')
            || request()->routeIs('usuarios.*')
            || request()->routeIs('roles.*')
            || request()->routeIs('ubicaciones.*');

  // Rutas seguras (si no existen create)
  $pacCreateRoute = \Illuminate\Support\Facades\Route::has('pacientes.create')
      ? 'pacientes.create'
      : 'pacientes.index';

  $medCreateRoute = \Illuminate\Support\Facades\Route::has('medios.create')
      ? 'medios.create'
      : 'medios.index';

  $proyCreateRoute = \Illuminate\Support\Facades\Route::has('avances.create')
      ? 'avances.create'
      : 'avances.index';

  $proyectosOpen = request()->routeIs('avances.*')
                 || request()->routeIs('presupuestos.*')
                 || request()->routeIs('facturas.*');
@endphp

<div>
  <div class="sidebar-title">Navegación</div>
</div>

<div class="navlist">

  {{-- ================= DASHBOARD (TODOS) ================= --}}
  <button type="button"
          class="navitem btn-reset {{ request()->routeIs('dashboard*') ? 'active' : '' }}"
          data-bs-toggle="collapse"
          data-bs-target="#{{ $idDashboard }}"
          aria-expanded="{{ request()->routeIs('dashboard*') ? 'true' : 'false' }}"
          aria-controls="{{ $idDashboard }}">
    <span class="navicon">📊</span>
    <span>Dashboard</span>
    <span class="ms-auto navcaret">▾</span>
  </button>

  <div class="collapse {{ request()->routeIs('dashboard*') ? 'show' : '' }}" id="{{ $idDashboard }}">
    <div class="d-grid gap-1 ms-4 mt-1">
      <a href="{{ route('dashboard') }}"
         class="navitem {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <span class="navicon">📈</span>
        <span>Donaciones</span>
      </a>
    </div>
  </div>

  {{-- ================= DONACIONES (ADMIN + GESTOR) ================= --}}
  @if($isAdmin || $isGestor)
    <button type="button"
            class="navitem btn-reset {{ $donOpen ? 'active' : '' }}"
            data-bs-toggle="collapse"
            data-bs-target="#{{ $idDonaciones }}"
            aria-expanded="{{ $donOpen ? 'true' : 'false' }}"
            aria-controls="{{ $idDonaciones }}">
      <span class="navicon">💝</span>
      <span>Donaciones</span>
      <span class="ms-auto navcaret">▾</span>
    </button>

    <div class="collapse {{ $donOpen ? 'show' : '' }}" id="{{ $idDonaciones }}">
      <div class="d-grid gap-1 ms-4 mt-1">

        <a href="{{ route('donaciones.index') }}"
           class="navitem {{ request()->routeIs('donaciones.index') ? 'active' : '' }}">
          <span class="navicon">📋</span>
          <span>Recibidas</span>
        </a>

     {{--   @if(\Illuminate\Support\Facades\Route::has('donaciones.create'))
          <a href="{{ route('donaciones.create') }}"
             class="navitem {{ request()->routeIs('donaciones.create') ? 'active' : '' }}">
            <span class="navicon">📝</span>
            <span>Ingresar</span>
          </a>
        @endif--}}

      </div>
    </div>
  @endif

  {{-- ================= PACIENTES (ADMIN + GESTOR) ================= --}}
  @if($isAdmin || $isGestor)
    <button type="button"
            class="navitem btn-reset {{ $pacOpen ? 'active' : '' }}"
            data-bs-toggle="collapse"
            data-bs-target="#{{ $idPacientes }}"
            aria-expanded="{{ $pacOpen ? 'true' : 'false' }}"
            aria-controls="{{ $idPacientes }}">
      <span class="navicon">🧑‍⚕️</span>
      <span>Pacientes</span>
      <span class="ms-auto navcaret">▾</span>
    </button>

    <div class="collapse {{ $pacOpen ? 'show' : '' }}" id="{{ $idPacientes }}">
      <div class="d-grid gap-1 ms-4 mt-1">

        <a href="{{ route('pacientes.index') }}"
           class="navitem {{ request()->routeIs('pacientes.index') ? 'active' : '' }}">
          <span class="navicon">📋</span>
          <span>Ingresados</span>
        </a>

      {{--  <a href="{{ route($pacCreateRoute) }}"
           class="navitem {{ request()->routeIs('pacientes.create') ? 'active' : '' }}">
          <span class="navicon">📝</span>
          <span>Registrar</span>
        </a>--}}

      </div>
    </div>
  @endif

  {{-- ================= MEDIOS (ADMIN + GESTOR) ================= --}}
  @if($isAdmin || $isGestor)
    <button type="button"
            class="navitem btn-reset {{ $medOpen ? 'active' : '' }}"
            data-bs-toggle="collapse"
            data-bs-target="#{{ $idMedios }}"
            aria-expanded="{{ $medOpen ? 'true' : 'false' }}"
            aria-controls="{{ $idMedios }}">
      <span class="navicon">🔔</span>
      <span>Medios</span>
      <span class="ms-auto navcaret">▾</span>
    </button>

    <div class="collapse {{ $medOpen ? 'show' : '' }}" id="{{ $idMedios }}">
      <div class="d-grid gap-1 ms-4 mt-1">

        <a href="{{ route('medios.index') }}"
           class="navitem {{ request()->routeIs('medios.index') ? 'active' : '' }}">
          <span class="navicon">📋</span>
          <span>Registrados</span>
        </a>

        {{--<a href="{{ route($medCreateRoute) }}"
           class="navitem {{ request()->routeIs('medios.create') ? 'active' : '' }}">
          <span class="navicon">➕</span>
          <span>Ingresar</span>
        </a>--}}


      </div>
    </div>
  @endif

  {{-- ================= PROYECTOS AAPOS (ADMIN + GESTOR) ================= --}}
  @if($isAdmin || $isGestor)
    <button type="button"
            class="navitem btn-reset {{ $proyOpen ? 'active' : '' }}"
            data-bs-toggle="collapse"
            data-bs-target="#{{ $idProyectosAapos }}"
            aria-expanded="{{ $proyOpen ? 'true' : 'false' }}"
            aria-controls="{{ $idProyectosAapos }}">
      <span class="navicon">📌</span>
      <span>Proyectos</span>
      <span class="ms-auto navcaret">▾</span>
    </button>

    <div class="collapse {{ $proyOpen ? 'show' : '' }}" id="{{ $idProyectosAapos }}">
      <div class="d-grid gap-1 ms-4 mt-1">
       <a href="{{ route('avances.create') }}"
        class="navitem {{ request()->routeIs('avances.create','avances.store','avances.byDate','avances.porFecha','avances.index') ? 'active' : '' }}">
        <span class="navicon">💻</span>
        <span>Avances</span>
        </a>

        <a href="{{ route('avances.dashboard') }}"
        class="navitem {{ request()->routeIs('avances.dashboard') ? 'active' : '' }}">
        <span class="navicon">📊</span>
        <span>Metricas</span>
        </a>

      </div>
    </div>
  @endif

  {{-- ================= MANTENIMIENTOS (SOLO ADMIN) ================= --}}
  @if($isAdmin)
    <button type="button"
            class="navitem btn-reset {{ $maintOpen ? 'active' : '' }}"
            data-bs-toggle="collapse"
            data-bs-target="#{{ $idMantenimientos }}"
            aria-expanded="{{ $maintOpen ? 'true' : 'false' }}"
            aria-controls="{{ $idMantenimientos }}">
      <span class="navicon">🛠️</span>
      <span>Mantenimientos</span>
      <span class="ms-auto navcaret">▾</span>
    </button>

    <div class="collapse {{ $maintOpen ? 'show' : '' }}" id="{{ $idMantenimientos }}">
      <div class="d-grid gap-1 ms-4 mt-1">

        <a href="{{ route('proyectos.index') }}"
           class="navitem {{ request()->routeIs('proyectos.*') ? 'active' : '' }}">
          <span class="navicon">📁</span>
          <span>Proyectos</span>
        </a>

        <a href="{{ route('tipos_donacion.index') }}"
           class="navitem {{ request()->routeIs('tipos_donacion.*') ? 'active' : '' }}">
          <span class="navicon">🎁</span>
          <span>Tipos de Donación</span>
        </a>

        <a href="{{ route('usuarios.index') }}"
           class="navitem {{ request()->routeIs('usuarios.*') ? 'active' : '' }}">
          <span class="navicon">🧑‍💻</span>
          <span>Usuarios</span>
        </a>

        <a href="{{ route('roles.index') }}"
           class="navitem {{ request()->routeIs('roles.*') ? 'active' : '' }}">
          <span class="navicon">🛡️</span>
          <span>Roles</span>
        </a>

        <a href="{{ route('ubicaciones.index') }}"
           class="navitem {{ request()->routeIs('ubicaciones.*') ? 'active' : '' }}">
          <span class="navicon"><i class="bi bi-geo-alt-fill"></i></span>
          <span>Ubicaciones</span>
        </a>

      </div>
    </div>
  @endif

  {{-- ================= CERRAR SESIÓN (TODOS) ================= --}}
  <form method="POST" action="{{ route('logout') }}" class="navlogout">
    @csrf
    <button type="submit" class="navitem btn-reset navlogout-btn">
      <span class="navicon">🚪</span>
      <span>Cerrar sesión</span>
      <span class="ms-auto navcaret navcaret-placeholder">▾</span>
    </button>
  </form>

</div>
