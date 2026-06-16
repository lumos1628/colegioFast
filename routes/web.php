<?php

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
    Route::get('/docente', fn () => view('portales.docente'))->name('docente');
    Route::get('/alumno', fn () => view('portales.alumno'))->name('alumno');
    Route::get('/padre', fn () => view('portales.padre'))->name('padre');
    Route::get('/psicologo', fn () => view('portales.psicologo'))->name('psicologo');
});

require __DIR__.'/auth.php';
