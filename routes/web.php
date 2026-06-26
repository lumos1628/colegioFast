<?php

use App\Http\Controllers\ActividadController;
use App\Http\Controllers\Admin\AlumnoController as AdminAlumnoController;
use App\Http\Controllers\Admin\AlumnoPadreController;
use App\Http\Controllers\Admin\AsignacionController as AdminAsignacionController;
use App\Http\Controllers\Admin\CursoController as AdminCursoController;
use App\Http\Controllers\Admin\PadreController as AdminPadreController;
use App\Http\Controllers\Admin\PeriodoAcademicoController as AdminPeriodoController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\Backoffice\DirectorController;
use App\Http\Controllers\Backoffice\PsicologoController;
use App\Http\Controllers\Backoffice\SecretariaController;
use App\Http\Controllers\DocenteController;
use App\Http\Controllers\IncidenciaConductaController;
use App\Http\Controllers\MatriculaController;
use App\Http\Controllers\NotaController;
use App\Http\Controllers\PadreController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReporteController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/admin', fn () => view('administrativo.admin'))->name('admin');
    Route::get('/director', [DirectorController::class, 'dashboard'])->name('director');
    Route::get('/secretaria', [SecretariaController::class, 'dashboard'])->name('secretaria');

    // Ecosistema Docente
    Route::prefix('docente')->name('docente.')->group(function () {
        Route::get('/', [DocenteController::class, 'dashboard'])->name('dashboard');
        Route::get('/horario', [DocenteController::class, 'horario'])->name('horario');
        Route::get('/actividades-pendientes', [DocenteController::class, 'actividadesPendientes'])->name('actividades-pendientes');
        Route::get('/cursos/{asignacion}', [DocenteController::class, 'showCurso'])->name('cursos.show');
        Route::get('/cursos/{asignacion}/alumnos/{alumno}', [DocenteController::class, 'showAlumno'])->name('cursos.alumnos.show');
        Route::get('/cursos/{asignacion}/alumnos/{alumno}/incidencias/crear', [IncidenciaConductaController::class, 'create'])->name('cursos.alumnos.incidencias.create');
        Route::post('/cursos/{asignacion}/alumnos/{alumno}/incidencias', [IncidenciaConductaController::class, 'store'])->name('cursos.alumnos.incidencias.store');
        Route::get('/cursos/{asignacion}/alumnos/{alumno}/incidencias/{incidencia}/editar', [IncidenciaConductaController::class, 'edit'])->name('cursos.alumnos.incidencias.edit');
        Route::put('/cursos/{asignacion}/alumnos/{alumno}/incidencias/{incidencia}', [IncidenciaConductaController::class, 'update'])->name('cursos.alumnos.incidencias.update');
        Route::delete('/cursos/{asignacion}/alumnos/{alumno}/incidencias/{incidencia}', [IncidenciaConductaController::class, 'destroy'])->name('cursos.alumnos.incidencias.destroy');

        Route::get('/cursos/{asignacion}/actividades', [ActividadController::class, 'index'])->name('cursos.actividades.index');
        Route::get('/cursos/{asignacion}/actividades/crear', [ActividadController::class, 'create'])->name('cursos.actividades.create');
        Route::post('/cursos/{asignacion}/actividades', [ActividadController::class, 'store'])->name('cursos.actividades.store');
        Route::get('/cursos/{asignacion}/actividades/{actividad}', [ActividadController::class, 'show'])->name('cursos.actividades.show');
        Route::get('/cursos/{asignacion}/actividades/{actividad}/editar', [ActividadController::class, 'edit'])->name('cursos.actividades.edit');
        Route::put('/cursos/{asignacion}/actividades/{actividad}', [ActividadController::class, 'update'])->name('cursos.actividades.update');
        Route::delete('/cursos/{asignacion}/actividades/{actividad}', [ActividadController::class, 'destroy'])->name('cursos.actividades.destroy');
        Route::post('/cursos/{asignacion}/actividades/{actividad}/notas', [NotaController::class, 'storeOrUpdate'])->name('cursos.actividades.notas.store');

        Route::get('/cursos/{asignacion}/asistencia', [AsistenciaController::class, 'index'])->name('cursos.asistencia.index');
        Route::post('/cursos/{asignacion}/asistencia', [AsistenciaController::class, 'store'])->name('cursos.asistencia.store');

        Route::get('/cursos/{asignacion}/reporte', [ReporteController::class, 'reporteCurso'])->name('cursos.reporte');
    });

    // Rutas administrativas (Admin, Director, Secretaria)
    Route::prefix('admin')->name('admin.')->group(function () {
        // Matrículas (CUS-14)
        Route::get('/matriculas', [MatriculaController::class, 'index'])->name('matriculas.index');
        Route::get('/matriculas/crear', [MatriculaController::class, 'create'])->name('matriculas.create');
        Route::post('/matriculas', [MatriculaController::class, 'store'])->name('matriculas.store');
        Route::delete('/matriculas/{matricula}', [MatriculaController::class, 'destroy'])->name('matriculas.destroy');

        // Alumnos (CUS-14)
        Route::get('/alumnos', [AdminAlumnoController::class, 'index'])->name('alumnos.index');
        Route::get('/alumnos/crear', [AdminAlumnoController::class, 'create'])->name('alumnos.create');
        Route::post('/alumnos', [AdminAlumnoController::class, 'store'])->name('alumnos.store');
        Route::get('/alumnos/{alumno}/editar', [AdminAlumnoController::class, 'edit'])->name('alumnos.edit');
        Route::put('/alumnos/{alumno}', [AdminAlumnoController::class, 'update'])->name('alumnos.update');
        Route::delete('/alumnos/{alumno}', [AdminAlumnoController::class, 'destroy'])->name('alumnos.destroy');

        // Padres (CUS-14)
        Route::get('/padres', [AdminPadreController::class, 'index'])->name('padres.index');
        Route::get('/padres/crear', [AdminPadreController::class, 'create'])->name('padres.create');
        Route::post('/padres', [AdminPadreController::class, 'store'])->name('padres.store');
        Route::get('/padres/{padre}/editar', [AdminPadreController::class, 'edit'])->name('padres.edit');
        Route::put('/padres/{padre}', [AdminPadreController::class, 'update'])->name('padres.update');
        Route::delete('/padres/{padre}', [AdminPadreController::class, 'destroy'])->name('padres.destroy');

        // Relación Alumno-Padre (CUS-14)
        Route::get('/alumnos/{alumno}/padres', [AlumnoPadreController::class, 'index'])->name('alumno-padre.index');
        Route::post('/alumnos/{alumno}/padres', [AlumnoPadreController::class, 'store'])->name('alumno-padre.store');
        Route::delete('/alumnos/{alumno}/padres/{padre}', [AlumnoPadreController::class, 'destroy'])->name('alumno-padre.destroy');

        // Periodos Académicos (CUS-15)
        Route::get('/periodos', [AdminPeriodoController::class, 'index'])->name('periodos.index');
        Route::post('/periodos', [AdminPeriodoController::class, 'store'])->name('periodos.store');
        Route::put('/periodos/{periodo}', [AdminPeriodoController::class, 'update'])->name('periodos.update');
        Route::post('/periodos/{periodo}/activar', [AdminPeriodoController::class, 'activar'])->name('periodos.activar');
        Route::delete('/periodos/{periodo}', [AdminPeriodoController::class, 'destroy'])->name('periodos.destroy');

        // Cursos (CUS-15)
        Route::get('/cursos', [AdminCursoController::class, 'index'])->name('cursos.index');
        Route::post('/cursos', [AdminCursoController::class, 'store'])->name('cursos.store');
        Route::put('/cursos/{curso}', [AdminCursoController::class, 'update'])->name('cursos.update');
        Route::delete('/cursos/{curso}', [AdminCursoController::class, 'destroy'])->name('cursos.destroy');

        // Asignaciones (CUS-15)
        Route::get('/asignaciones', [AdminAsignacionController::class, 'index'])->name('asignaciones.index');
        Route::get('/asignaciones/crear', [AdminAsignacionController::class, 'create'])->name('asignaciones.create');
        Route::post('/asignaciones', [AdminAsignacionController::class, 'store'])->name('asignaciones.store');
        Route::get('/asignaciones/{asignacion}/editar', [AdminAsignacionController::class, 'edit'])->name('asignaciones.edit');
        Route::put('/asignaciones/{asignacion}', [AdminAsignacionController::class, 'update'])->name('asignaciones.update');
        Route::delete('/asignaciones/{asignacion}', [AdminAsignacionController::class, 'destroy'])->name('asignaciones.destroy');

        // Reportes (CUS-16)
        Route::get('/reportes/libreta/{alumno}', [ReporteController::class, 'libreta'])->name('reportes.libreta');
    });

    // Ecosistema Alumno
    Route::prefix('alumno')->name('alumno.')->group(function () {
        Route::get('/', [AlumnoController::class, 'dashboard'])->name('dashboard');
        Route::get('/cursos/{asignacion}', [AlumnoController::class, 'showCurso'])->name('cursos.show');
    });

    // Ecosistema Padre
    Route::prefix('padre')->name('padre.')->group(function () {
        Route::get('/', [PadreController::class, 'dashboard'])->name('dashboard');
        Route::get('/hijos/{alumno}', [PadreController::class, 'showHijo'])->name('hijos.show');
        Route::get('/notificaciones', [PadreController::class, 'notificaciones'])->name('notificaciones');
        Route::post('/notificaciones/{notificacion}/leida', [PadreController::class, 'marcarLeida'])->name('notificaciones.leida');
        Route::get('/pagos', [PadreController::class, 'pagos'])->name('pagos');
    });

    // Ecosistema Psicólogo (CUS-17)
    Route::prefix('psicologo')->name('psicologo.')->group(function () {
        Route::get('/', [PsicologoController::class, 'dashboard'])->name('dashboard');
        Route::get('/bitacoras', [PsicologoController::class, 'bitacoras'])->name('bitacoras.index');
        Route::get('/bitacoras/crear', [PsicologoController::class, 'create'])->name('bitacoras.create');
        Route::post('/bitacoras', [PsicologoController::class, 'store'])->name('bitacoras.store');
        Route::get('/bitacoras/{bitacora}/editar', [PsicologoController::class, 'edit'])->name('bitacoras.edit');
        Route::put('/bitacoras/{bitacora}', [PsicologoController::class, 'update'])->name('bitacoras.update');
        Route::delete('/bitacoras/{bitacora}', [PsicologoController::class, 'destroy'])->name('bitacoras.destroy');
    });
});

require __DIR__.'/auth.php';
