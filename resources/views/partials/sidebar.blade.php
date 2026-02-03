@php
  $u = session('user'); // array guardado en sesión

  // Normalizamos rol desde sesión
  $rolName = strtoupper(trim($u['rol'] ?? $u['nombre_rol'] ?? ''));
  $rolId   = (int)($u['id_rol'] ?? 0);

  /*
    Permisos por rol (según tu tabla):
    - ADMIN: TODO
    - GESTOR: TODO, excepto Usuarios y Roles
    - SECRETARIA: Pacientes, Medios, Proyectos
    - PROYECTOS: Proyectos
    - DIRECTOR: Dashboard, Metricas
  */

  // Identificadores base
  $isAdmin      = ($rolId === 1) || $rolName === 'ADMIN';
  $isGestor     = in_array($rolName, ['GESTOR', 'DONACIONES'], true);
  $isSecretaria = $rolName === 'SECRETARIA';
  $isProyectos  = $rolName === 'PROYECTOS';
  $isDirector   = $rolName === 'DIRECTOR';

  // Permisos por módulo
  $canDashboard  = $isAdmin || $isGestor || $isDirector || $isSecretaria ;
  $canDonaciones = $isAdmin || $isGestor;
  $canPacientes  = $isAdmin || $isGestor || $isSecretaria;
  $canMedios     = $isAdmin || $isGestor || $isSecretaria;

  $canProyectos  = $isAdmin || $isGestor || $isSecretaria || $isProyectos || $isDirector;

  // Subpermisos dentro de Proyectos
  $canAvances    = $isAdmin || $isGestor || $isSecretaria || $isProyectos;
  $canMetricas   = $isAdmin || $isGestor || $isDirector;

  // Oficina (si quieres limitarlo por rol, aquí lo haces)
  $canOficina    = $isAdmin || $isGestor || $isSecretaria || $isDirector; // ajustable

  // Mantenimientos
  $canMaint      = $isAdmin || $isGestor;

  $canMaintProyectos     = $isAdmin || $isGestor;
  $canMaintTiposDonacion = $isAdmin || $isGestor;
  $canMaintUbicaciones   = $isAdmin || $isGestor;

  // Gestor no puede Usuarios ni Roles
  $canMaintUsuarios = $isAdmin;
  $canMaintRoles    = $isAdmin;

  // SCOPE
  $scope = $scope ?? 'desk';

  // IDs únicos por menú
  $idDashboard      = "menuDashboard-{$scope}";
  $idDonaciones     = "menuDonaciones-{$scope}";
  $idPacientes      = "menuPacientes-{$scope}";
  $idMedios         = "menuMedios-{$scope}";
  $idProyectosAapos = "menuProyectosAapos-{$scope}";
  $idOficina        = "menuOficina-{$scope}";
  $idMantenimientos = "menuMantenimientos-{$scope}";

  // Para abrir submenús cuando estás dentro
  $donOpen  = request()->routeIs('donaciones.*');
  $pacOpen  = request()->routeIs('pacientes.*');
  $medOpen  = request()->routeIs('medios.*');

  $proyOpen = request()->routeIs('avances.*')
          || request()->routeIs('presupuestos.*')
          || request()->routeIs('facturas.*')
          || request()->routeIs('proyectosaapos.*');

  $ofOpen = request()->routeIs('oficina.antigua.*')
         || request()->routeIs('oficina.rambla.*');

  $maintOpen = request()->routeIs('proyectos.*')
            || request()->routeIs('tipos_donacion.*')
            || request()->routeIs('usuarios.*')
            || request()->routeIs('roles.*')
            || request()->routeIs('ubicaciones.*');
@endphp

<div>
  <div class="sidebar-title">Navegación</div>
</div>

<div class="navlist">

  {{-- ================= DASHBOARD ================= --}}
  @if($canDashboard)
    <button type="button"
            class="navitem btn-reset {{ request()->routeIs('dashboard*') ? 'active' : '' }}"
            data-bs-toggle="collapse"
            data-bs-target="#{{ $idDashboard }}"
            aria-expanded="{{ request()->routeIs('dashboard*') ? 'true' : 'false' }}"
            aria-controls="{{ $idDashboard }}"
            data-bs-toggle="tooltip"
            data-bs-placement="right"
            data-bs-container="body"
            title="Dashboard y métricas">
      <span class="navicon">📊</span>
      <span>Dashboard</span>
      <span class="ms-auto navcaret">▾</span>
    </button>

    <div class="collapse {{ request()->routeIs('dashboard*') ? 'show' : '' }}" id="{{ $idDashboard }}">
      <div class="d-grid gap-1 ms-4 mt-1">
        <a href="{{ route('dashboard') }}"
           class="navitem {{ request()->routeIs('dashboard') ? 'active' : '' }}"
           data-bs-toggle="tooltip"
           data-bs-placement="right"
           data-bs-container="body"
           title="Ver resumen general">
          <span class="navicon">📈</span>
          <span>Resumen</span>
        </a>
      </div>
    </div>
  @endif

  {{-- ================= DONACIONES ================= --}}
  @if($canDonaciones)
    <button type="button"
            class="navitem btn-reset {{ $donOpen ? 'active' : '' }}"
            data-bs-toggle="collapse"
            data-bs-target="#{{ $idDonaciones }}"
            aria-expanded="{{ $donOpen ? 'true' : 'false' }}"
            aria-controls="{{ $idDonaciones }}"
            data-bs-toggle="tooltip"
            data-bs-placement="right"
            data-bs-container="body"
            title="Gestión de donaciones">
      <span class="navicon">💝</span>
      <span>Donaciones</span>
      <span class="ms-auto navcaret">▾</span>
    </button>

    <div class="collapse {{ $donOpen ? 'show' : '' }}" id="{{ $idDonaciones }}">
      <div class="d-grid gap-1 ms-4 mt-1">
        <a href="{{ route('donaciones.index') }}"
           class="navitem {{ request()->routeIs('donaciones.index') ? 'active' : '' }}"
           data-bs-toggle="tooltip"
           data-bs-placement="right"
           data-bs-container="body"
           title="Lista de donaciones recibidas">
          <span class="navicon">📋</span>
          <span>Recibidas</span>
        </a>
      </div>
    </div>
  @endif

  {{-- ================= PACIENTES ================= --}}
  @if($canPacientes)
    <button type="button"
            class="navitem btn-reset {{ $pacOpen ? 'active' : '' }}"
            data-bs-toggle="collapse"
            data-bs-target="#{{ $idPacientes }}"
            aria-expanded="{{ $pacOpen ? 'true' : 'false' }}"
            aria-controls="{{ $idPacientes }}"
            data-bs-toggle="tooltip"
            data-bs-placement="right"
            data-bs-container="body"
            title="Gestión de pacientes">
      <span class="navicon">🧑‍⚕️</span>
      <span>Pacientes</span>
      <span class="ms-auto navcaret">▾</span>
    </button>

    <div class="collapse {{ $pacOpen ? 'show' : '' }}" id="{{ $idPacientes }}">
      <div class="d-grid gap-1 ms-4 mt-1">
        <a href="{{ route('pacientes.index') }}"
           class="navitem {{ request()->routeIs('pacientes.index') ? 'active' : '' }}"
           data-bs-toggle="tooltip"
           data-bs-placement="right"
           data-bs-container="body"
           title="Ver pacientes ingresados">
          <span class="navicon">📋</span>
          <span>Ingresados</span>
        </a>
      </div>
    </div>
  @endif

  {{-- ================= MEDIOS ================= --}}
  @if($canMedios)
    <button type="button"
            class="navitem btn-reset {{ $medOpen ? 'active' : '' }}"
            data-bs-toggle="collapse"
            data-bs-target="#{{ $idMedios }}"
            aria-expanded="{{ $medOpen ? 'true' : 'false' }}"
            aria-controls="{{ $idMedios }}"
            data-bs-toggle="tooltip"
            data-bs-placement="right"
            data-bs-container="body"
            title="Medios y contactos">
      <span class="navicon">🔔</span>
      <span>Medios</span>
      <span class="ms-auto navcaret">▾</span>
    </button>

    <div class="collapse {{ $medOpen ? 'show' : '' }}" id="{{ $idMedios }}">
      <div class="d-grid gap-1 ms-4 mt-1">
        <a href="{{ route('medios.index') }}"
           class="navitem {{ request()->routeIs('medios.index') ? 'active' : '' }}"
           data-bs-toggle="tooltip"
           data-bs-placement="right"
           data-bs-container="body"
           title="Ver medios registrados">
          <span class="navicon">📋</span>
          <span>Registrados</span>
        </a>
      </div>
    </div>
  @endif

  {{-- ================= PROYECTOS ================= --}}
  @if($canProyectos)
    <button type="button"
            class="navitem btn-reset {{ $proyOpen ? 'active' : '' }}"
            data-bs-toggle="collapse"
            data-bs-target="#{{ $idProyectosAapos }}"
            aria-expanded="{{ $proyOpen ? 'true' : 'false' }}"
            aria-controls="{{ $idProyectosAapos }}"
            data-bs-toggle="tooltip"
            data-bs-placement="right"
            data-bs-container="body"
            title="Módulo de proyectos">
      <span class="navicon">📌</span>
      <span>Proyectos</span>
      <span class="ms-auto navcaret">▾</span>
    </button>

    <div class="collapse {{ $proyOpen ? 'show' : '' }}" id="{{ $idProyectosAapos }}">
      <div class="d-grid gap-1 ms-4 mt-1">

        @if($canAvances)
          <a href="{{ route('avances.create') }}"
             class="navitem {{ request()->routeIs('avances.create','avances.store','avances.byDate','avances.porFecha','avances.index') ? 'active' : '' }}"
             data-bs-toggle="tooltip"
             data-bs-placement="right"
             data-bs-container="body"
             title="Registrar y consultar avances">
            <span class="navicon">💻</span>
            <span>Avances</span>
          </a>
        @endif

        @if($canMetricas)
          <a href="{{ route('avances.dashboard') }}"
             class="navitem {{ request()->routeIs('avances.dashboard') ? 'active' : '' }}"
             data-bs-toggle="tooltip"
             data-bs-placement="right"
             data-bs-container="body"
             title="Ver métricas de avances">
            <span class="navicon">📊</span>
            <span>Métricas</span>
          </a>
        @endif

      </div>
    </div>
  @endif

  {{-- ================= OFICINA ================= --}}
  @if($canOficina)
    <button type="button"
            class="navitem btn-reset {{ $ofOpen ? 'active' : '' }}"
            data-bs-toggle="collapse"
            data-bs-target="#{{ $idOficina }}"
            aria-expanded="{{ $ofOpen ? 'true' : 'false' }}"
            aria-controls="{{ $idOficina }}"
            data-bs-toggle="tooltip"
            data-bs-placement="right"
            data-bs-container="body"
            title="Documentos por oficina">
      <span class="navicon">🏢</span>
      <span>Oficina</span>
      <span class="ms-auto navcaret">▾</span>
    </button>

    <div class="collapse {{ $ofOpen ? 'show' : '' }}" id="{{ $idOficina }}">
      <div class="d-grid gap-1 ms-4 mt-1">

        <a href="{{ route('oficina.antigua.index') }}"
           class="navitem {{ request()->routeIs('oficina.antigua.*') ? 'active' : '' }}"
           data-bs-toggle="tooltip"
           data-bs-placement="right"
           data-bs-container="body"
           title="Documentos de Oficina Antigua">
          <span class="navicon">📂</span>
          <span>Antigua</span>
        </a>

        <a href="{{ route('oficina.rambla.index') }}"
           class="navitem {{ request()->routeIs('oficina.rambla.*') ? 'active' : '' }}"
           data-bs-toggle="tooltip"
           data-bs-placement="right"
           data-bs-container="body"
           title="Documentos de Oficina Zona 14">
          <span class="navicon">📂</span>
          <span>Rambla</span>
        </a>

      </div>
    </div>
  @endif

  {{-- ================= MANTENIMIENTOS ================= --}}
  @if($canMaint)
    <button type="button"
            class="navitem btn-reset {{ $maintOpen ? 'active' : '' }}"
            data-bs-toggle="collapse"
            data-bs-target="#{{ $idMantenimientos }}"
            aria-expanded="{{ $maintOpen ? 'true' : 'false' }}"
            aria-controls="{{ $idMantenimientos }}"
            data-bs-toggle="tooltip"
            data-bs-placement="right"
            data-bs-container="body"
            title="Catálogos y configuración">
      <span class="navicon">🛠️</span>
      <span>Mantenimientos</span>
      <span class="ms-auto navcaret">▾</span>
    </button>

    <div class="collapse {{ $maintOpen ? 'show' : '' }}" id="{{ $idMantenimientos }}">
      <div class="d-grid gap-1 ms-4 mt-1">

        @if($canMaintProyectos)
          <a href="{{ route('proyectos.index') }}"
             class="navitem {{ request()->routeIs('proyectos.*') ? 'active' : '' }}"
             data-bs-toggle="tooltip"
             data-bs-placement="right"
             data-bs-container="body"
             title="Catálogo de proyectos">
            <span class="navicon">📁</span>
            <span>Proyectos</span>
          </a>
        @endif

        @if($canMaintTiposDonacion)
          <a href="{{ route('tipos_donacion.index') }}"
             class="navitem {{ request()->routeIs('tipos_donacion.*') ? 'active' : '' }}"
             data-bs-toggle="tooltip"
             data-bs-placement="right"
             data-bs-container="body"
             title="Tipos de donación">
            <span class="navicon">🎁</span>
            <span>Tipos de Donación</span>
          </a>
        @endif

        @if($canMaintUsuarios)
          <a href="{{ route('usuarios.index') }}"
             class="navitem {{ request()->routeIs('usuarios.*') ? 'active' : '' }}"
             data-bs-toggle="tooltip"
             data-bs-placement="right"
             data-bs-container="body"
             title="Administrar usuarios">
            <span class="navicon">🧑‍💻</span>
            <span>Usuarios</span>
          </a>
        @endif

        @if($canMaintRoles)
          <a href="{{ route('roles.index') }}"
             class="navitem {{ request()->routeIs('roles.*') ? 'active' : '' }}"
             data-bs-toggle="tooltip"
             data-bs-placement="right"
             data-bs-container="body"
             title="Administrar roles">
            <span class="navicon">🛡️</span>
            <span>Roles</span>
          </a>
        @endif

        @if($canMaintUbicaciones)
          <a href="{{ route('ubicaciones.index') }}"
             class="navitem {{ request()->routeIs('ubicaciones.*') ? 'active' : '' }}"
             data-bs-toggle="tooltip"
             data-bs-placement="right"
             data-bs-container="body"
             title="Catálogo de ubicaciones">
            <span class="navicon"><i class="bi bi-geo-alt-fill"></i></span>
            <span>Ubicaciones</span>
          </a>
        @endif

      </div>
    </div>
  @endif

  {{-- ================= CERRAR SESIÓN ================= --}}
  <form method="POST" action="{{ route('logout') }}" class="navlogout">
    @csrf
    <button type="submit"
            class="navitem btn-reset navlogout-btn"
            data-bs-toggle="tooltip"
            data-bs-placement="right"
            data-bs-container="body"
            title="Cerrar la sesión actual">
      <span class="navicon">🚪</span>
      <span>Cerrar sesión</span>
      <span class="ms-auto navcaret navcaret-placeholder">▾</span>
    </button>
  </form>

</div>
