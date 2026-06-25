<?php

use App\Http\Controllers\ActividadController;
use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\DocenteController;
use App\Http\Controllers\NotaController;
use App\Http\Controllers\ProfileController;
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
    Route::get('/director', fn () => view('administrativo.director'))->name('director');
    Route::get('/secretaria', fn () => view('administrativo.secretaria'))->name('secretaria');
    Route::get('/psicologo', fn () => view('portales.psicologo'))->name('psicologo');

    // Ecosistema Docente
    Route::prefix('docente')->name('docente.')->group(function () {
        Route::get('/', [DocenteController::class, 'dashboard'])->name('dashboard');
        Route::get('/horario', [DocenteController::class, 'horario'])->name('horario');
        Route::get('/cursos/{asignacion}', [DocenteController::class, 'showCurso'])->name('cursos.show');
        Route::get('/cursos/{asignacion}/alumnos/{alumno}', [DocenteController::class, 'showAlumno'])->name('cursos.alumnos.show');

        Route::get('/cursos/{asignacion}/actividades', [ActividadController::class, 'index'])->name('cursos.actividades.index');
        Route::get('/cursos/{asignacion}/actividades/crear', [ActividadController::class, 'create'])->name('cursos.actividades.create');
        Route::post('/cursos/{asignacion}/actividades', [ActividadController::class, 'store'])->name('cursos.actividades.store');
        Route::get('/cursos/{asignacion}/actividades/{actividad}', [ActividadController::class, 'show'])->name('cursos.actividades.show');
        Route::post('/cursos/{asignacion}/actividades/{actividad}/notas', [NotaController::class, 'storeOrUpdate'])->name('cursos.actividades.notas.store');

        Route::get('/cursos/{asignacion}/asistencia', [AsistenciaController::class, 'index'])->name('cursos.asistencia.index');
        Route::post('/cursos/{asignacion}/asistencia', [AsistenciaController::class, 'store'])->name('cursos.asistencia.store');
    });

    Route::get('/alumno', fn () => view('portales.alumno'))->name('alumno');
    Route::get('/padre', fn () => view('portales.padre'))->name('padre');
});

require __DIR__.'/auth.php';
