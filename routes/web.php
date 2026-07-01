<?php

use App\Http\Controllers\ActividadController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\Backoffice\DirectorController;
use App\Http\Controllers\Backoffice\PsicologoController;
use App\Http\Controllers\DocenteController;
use App\Http\Controllers\IncidenciaConductaController;
use App\Http\Controllers\NotaController;
use App\Http\Controllers\PadreController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReporteController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/director', [DirectorController::class, 'dashboard'])->name('director');

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

    // Ecosistema Psicólogo
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
