<?php

use App\Models\Asignacion;
use App\Models\Docente;
use App\Models\Matricula;
use App\Models\PeriodoAcademico;

test('docente autenticado puede ver su dashboard', function () {
    $docente = Docente::factory()->create();

    $response = $this->actingAs($docente->user)
        ->get(route('docente.dashboard'));

    $response->assertOk();
    $response->assertSee('Mis Cursos');
    $response->assertSee($docente->user->name);
});

test('dashboard muestra cursos del periodo activo', function () {
    $docente = Docente::factory()->create();
    $periodo = PeriodoAcademico::factory()->create(['activo' => true]);
    $asignacion = Asignacion::factory()->create([
        'docente_id' => $docente->id,
        'periodo_academico_id' => $periodo->id,
    ]);

    $response = $this->actingAs($docente->user)
        ->get(route('docente.dashboard'));

    $response->assertOk();
    $response->assertSee($asignacion->curso->nombre);
});

test('dashboard no muestra cursos de periodos cerrados', function () {
    $docente = Docente::factory()->create();
    $periodoCerrado = PeriodoAcademico::factory()->create(['activo' => false]);
    $asignacion = Asignacion::factory()->create([
        'docente_id' => $docente->id,
        'periodo_academico_id' => $periodoCerrado->id,
    ]);

    $response = $this->actingAs($docente->user)
        ->get(route('docente.dashboard'));

    $response->assertOk();
    $response->assertDontSee($asignacion->curso->nombre);
});

test('docente puede ver detalle de un curso con alumnos', function () {
    $docente = Docente::factory()->create();
    $asignacion = Asignacion::factory()->create(['docente_id' => $docente->id]);
    $matricula = Matricula::factory()->create(['asignacion_id' => $asignacion->id]);

    $response = $this->actingAs($docente->user)
        ->get(route('docente.cursos.show', $asignacion));

    $response->assertOk();
    $response->assertSee($asignacion->curso->nombre);
    $response->assertSee($matricula->alumno->nombres);
    $response->assertSee($matricula->alumno->apellido_paterno);
});

test('docente no puede ver curso de otro docente', function () {
    $docente1 = Docente::factory()->create();
    $docente2 = Docente::factory()->create();
    $asignacion = Asignacion::factory()->create(['docente_id' => $docente2->id]);

    $response = $this->actingAs($docente1->user)
        ->get(route('docente.cursos.show', $asignacion));

    $response->assertForbidden();
});

test('usuario no autenticado no puede acceder al dashboard', function () {
    $response = $this->get(route('docente.dashboard'));

    $response->assertRedirect('/login');
});
