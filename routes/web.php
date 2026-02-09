<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RifaController;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\DonacionController;
use App\Http\Controllers\TipoDonacionController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\UbicacionController;
use App\Http\Controllers\PacientesController;
use App\Http\Controllers\MediosController;
use App\Http\Controllers\ProyectosAAPOSController;
use App\Http\Controllers\CalidadVidaController;
use App\Http\Controllers\AvanceController;
 use App\Http\Controllers\ProyectoUsuarioController;

// ✅ Estos dos te faltaban en el archivo que me pasaste (si ya existen, perfecto)
use App\Http\Controllers\DocumentoAntiguaController;
use App\Http\Controllers\DocumentoZona14Controller;
use App\Http\Controllers\OficinaAntiguaController;
use App\Http\Controllers\RubroController;

use Illuminate\Support\Facades\Schedule;

Route::get('/', function () {
    return session()->has('user')
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'show'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth.custom'])->group(function () {

    // =========================
    // DASHBOARD (TODOS LOGUEADOS)
    // =========================
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/dashboard/scatter', [DashboardController::class, 'scatter'])
        ->name('dashboard.scatter');

    // =========================
    // DONACIONES (ADMIN + GESTOR + DONACIONES)
    // =========================
    Route::middleware(['role:ADMIN,GESTOR,DONACIONES'])->group(function () {

        Route::get('/donaciones/index', [DonacionController::class, 'index'])->name('donaciones.index');
        Route::get('/donaciones/crear', [DonacionController::class, 'create'])->name('donaciones.create');
        Route::post('/donaciones', [DonacionController::class, 'store'])->name('donaciones.store');

        Route::get('/donaciones/{id}/edit', [DonacionController::class, 'edit'])->name('donaciones.edit');
        Route::put('/donaciones/{id}', [DonacionController::class, 'update'])->name('donaciones.update');
        Route::delete('/donaciones/{id}', [DonacionController::class, 'destroy'])->name('donaciones.destroy');

        Route::get('/donaciones/{id}', [DonacionController::class, 'show'])->name('donaciones.show');

        Route::get('/donaciones/export/excel', [DashboardController::class, 'exportExcel'])
            ->name('donaciones.export.excel');

        Route::get('/donaciones/export/pdf', [DashboardController::class, 'exportPdf'])
            ->name('donaciones.export.pdf');

        Route::get('/donaciones/{id}/pdf', [DonacionController::class, 'pdf'])->name('donaciones.pdf');

        Route::post('/donaciones/{id}/toggle-bloqueo', [DonacionController::class, 'toggleBloqueo'])
            ->name('donaciones.toggleBloqueo');

        Route::get('/asignaciones/proyectos-usuarios', [ProyectoUsuarioController::class, 'index'])
    ->name('asignaciones.proyectos_usuarios.index');

        Route::get('/asignaciones/proyectos-usuarios/{id_usuario}/edit', [ProyectoUsuarioController::class, 'edit'])
            ->name('asignaciones.proyectos_usuarios.edit');

        Route::put('/asignaciones/proyectos-usuarios/{id_usuario}', [ProyectoUsuarioController::class, 'update'])
            ->name('asignaciones.proyectos_usuarios.update');


           Route::get('/proyectos/export', [ProyectoController::class, 'exportExcel'])
        ->name('proyectos.export');

    Route::resource('proyectos', ProyectoController::class);


        Schedule::command('bloquear:donaciones-mensuales')->dailyAt('23:59');
    });

    // =========================
    // RIFA (ADMIN + RIFA)
    // =========================
    Route::middleware(['role:ADMIN,RIFA'])->group(function () {
        Route::get('/rifa', [RifaController::class, 'index'])->name('rifa.index');
    });

    // =========================
    // MANTENIMIENTOS (ADMIN + GESTOR + DONACIONES)
    // =========================
    Route::middleware(['role:ADMIN,GESTOR,DONACIONES'])->group(function () {
        Route::resource('proyectos', ProyectoController::class);
        Route::resource('tipos_donacion', TipoDonacionController::class);

        Route::resource('ubicaciones', UbicacionController::class)
            ->parameters(['ubicaciones' => 'ubicacion']);

                    Route::get('/asignaciones/proyectos-usuarios', [ProyectoUsuarioController::class, 'index'])
    ->name('asignaciones.proyectos_usuarios.index');

        Route::get('/asignaciones/proyectos-usuarios/{id_usuario}/edit', [ProyectoUsuarioController::class, 'edit'])
            ->name('asignaciones.proyectos_usuarios.edit');

        Route::put('/asignaciones/proyectos-usuarios/{id_usuario}', [ProyectoUsuarioController::class, 'update'])
            ->name('asignaciones.proyectos_usuarios.update');

        Route::get('/donaciones/export/excel', [DashboardController::class, 'exportExcel'])
            ->name('donaciones.export.excel');

        Route::get('/donaciones/export/pdf', [DashboardController::class, 'exportPdf'])
            ->name('donaciones.export.pdf');

        Route::get('/donaciones/{id}/pdf', [DonacionController::class, 'pdf'])->name('donaciones.pdf');


    });

    // =========================
    // MANTENIMIENTOS (SOLO ADMIN)
    // =========================
    Route::middleware(['role:ADMIN'])->group(function () {
        Route::resource('usuarios', UsuarioController::class);
        Route::resource('roles', RolController::class);
    });

    // =========================
    // PACIENTES / MEDIOS (según tu lógica actual, están abiertos a logueados)
    // =========================
    Route::get('/pacientes', [PacientesController::class, 'index'])->name('pacientes.index');
    Route::get('/pacientes/crear', [PacientesController::class, 'create'])->name('pacientes.create');
    Route::post('/pacientes', [PacientesController::class, 'store'])->name('pacientes.store');

    Route::get('/pacientes/{id}/editar', [PacientesController::class, 'edit'])->name('pacientes.edit');
    Route::put('/pacientes/{id}', [PacientesController::class, 'update'])->name('pacientes.update');
    Route::delete('/pacientes/{id}', [PacientesController::class, 'destroy'])->name('pacientes.destroy');
    Route::get('/pacientes/{id}', [PacientesController::class, 'show'])->name('pacientes.show');

    Route::get('/pacientes/export/excel', [PacientesController::class, 'exportExcel'])
        ->name('pacientes.export.excel');

    Route::get('/medios', [MediosController::class, 'index'])->name('medios.index');
    Route::get('/medios/crear', [MediosController::class, 'create'])->name('medios.create');
    Route::post('/medios', [MediosController::class, 'store'])->name('medios.store');

    Route::get('/medios/{id}/editar', [MediosController::class, 'edit'])->name('medios.edit');
    Route::put('/medios/{id}', [MediosController::class, 'update'])->name('medios.update');
    Route::delete('/medios/{id}', [MediosController::class, 'destroy'])->name('medios.destroy');

    Route::get('/medios/{id}', [MediosController::class, 'show'])->name('medios.show');
    Route::get('/medios/export/excel', [MediosController::class, 'exportExcel'])->name('medios.export.excel');

    // =========================
    // PROYECTOS AAPOS / CALIDAD VIDA
    // =========================
    Route::get('/proyectosaapos', [ProyectosAAPOSController::class, 'index'])->name('proyectosaapos.index');

    Route::get('/proyectosaapos/calidadvida', [CalidadVidaController::class, 'index'])->name('calidadvida.index');
    Route::post('/proyectosaapos/calidadvida', [CalidadVidaController::class, 'store'])->name('calidadvida.store');
    Route::put('/proyectosaapos/calidadvida/{item}', [CalidadVidaController::class, 'update'])->name('calidadvida.update');
    Route::delete('/proyectosaapos/calidadvida/{item}', [CalidadVidaController::class, 'destroy'])->name('calidadvida.destroy');

    // =========================
    // AVANCES
    // =========================
    Route::get('/avances', [AvanceController::class, 'create'])->name('avances.create');
    Route::post('/avances', [AvanceController::class, 'store'])->name('avances.store');
    Route::get('/avances/por-fecha', [AvanceController::class, 'byDate'])->name('avances.byDate');

    Route::post('/avances/upload-image', [AvanceController::class, 'uploadImage'])
        ->name('avances.uploadImage');

    Route::get('/avances/dashboard', [AvanceController::class, 'dashboard'])->name('avances.dashboard');

    Route::get('/avances/export/excel', [AvanceController::class, 'exportExcel'])
        ->name('avances.export.excel');

    Route::get('/avances/export-pdf', [AvanceController::class, 'exportPdf'])->name('avances.exportPdf');


    Route::get('/rubros', [RubroController::class, 'index'])->name('rubros.index');
    Route::get('/rubros/create', [RubroController::class, 'create'])->name('rubros.create');
    Route::post('/rubros', [RubroController::class, 'store'])->name('rubros.store');
    Route::get('/rubros/{id}/edit', [RubroController::class, 'edit'])->name('rubros.edit');
    Route::put('/rubros/{id}', [RubroController::class, 'update'])->name('rubros.update');
    Route::delete('/rubros/{id}', [RubroController::class, 'destroy'])->name('rubros.destroy');

    Route::patch('/rubros/{id}/toggle', [RubroController::class, 'toggle'])->name('rubros.toggle');


    // =========================
    // OFICINA
    // =========================
    Route::get('/oficina/antigua', [DocumentoAntiguaController::class, 'index'])
        ->name('oficina.antigua.index');

    Route::get('/oficina/rambla', [DocumentoZona14Controller::class, 'index'])
        ->name('oficina.rambla.index');

    Route::get('/', [OficinaAntiguaController::class, 'index'])->name('oficina.antigua.index');

    // ✅ Export (antes de {id})
    Route::get('/export/excel', [OficinaAntiguaController::class, 'exportExcel'])
        ->name('oficina.antigua.export.excel');

    // ✅ Create (antes de {id})
    Route::get('/create', [OficinaAntiguaController::class, 'create'])->name('oficina.antigua.create');
    Route::post('/', [OficinaAntiguaController::class, 'store'])->name('oficina.antigua.store');

    // ✅ Show (detalle) — IMPORTANTE: antes de edit/update/destroy también funciona, pero siempre DESPUÉS de create/export
    Route::get('/{id}', [OficinaAntiguaController::class, 'show'])->name('oficina.antigua.show');

    // ✅ Edit/Update/Delete
    Route::get('/{id}/edit', [OficinaAntiguaController::class, 'edit'])->name('oficina.antigua.edit');
    Route::put('/{id}', [OficinaAntiguaController::class, 'update'])->name('oficina.antigua.update');
    Route::delete('/{id}', [OficinaAntiguaController::class, 'destroy'])->name('oficina.antigua.destroy');









});
