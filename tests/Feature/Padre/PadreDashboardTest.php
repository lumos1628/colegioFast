<?php

use App\Models\Actividad;
use App\Models\Alumno;
use App\Models\Asignacion;
use App\Models\Matricula;
use App\Models\Nota;
use App\Models\Padre;
use App\Models\PeriodoAcademico;

test('padre autenticado puede ver su dashboard', function () {
    $padre = Padre::factory()->create();

    $response = $this->actingAs($padre->user)
        ->get(route('padre.dashboard'));

    $response->assertOk();
    $response->assertSee('Panel de Padres');
});

test('padre ve hijos tutorados en dashboard', function () {
    $padre = Padre::factory()->create();
    $alumno = Alumno::factory()->create();
    $padre->alumnos()->attach($alumno->id, ['parentesco' => 'padre']);

    $response = $this->actingAs($padre->user)
        ->get(route('padre.dashboard'));

    $response->assertOk();
    $response->assertSee($alumno->nombres);
    $response->assertSee($alumno->apellido_paterno);
});

test('padre puede ver detalle de hijo tutorado', function () {
    $padre = Padre::factory()->create();
    $alumno = Alumno::factory()->create();
    $padre->alumnos()->attach($alumno->id, ['parentesco' => 'padre']);

    $response = $this->actingAs($padre->user)
        ->get(route('padre.hijos.show', $alumno));

    $response->assertOk();
    $response->assertSee($alumno->nombres);
});

test('padre no puede ver hijo no tutorado', function () {
    $padre = Padre::factory()->create();
    $alumno = Alumno::factory()->create();

    $response = $this->actingAs($padre->user)
        ->get(route('padre.hijos.show', $alumno));

    $response->assertForbidden();
});

test('padre ve todas las notas del hijo incluyendo ocultas', function () {
    $padre = Padre::factory()->create();
    $alumno = Alumno::factory()->create();
    $padre->alumnos()->attach($alumno->id, ['parentesco' => 'padre']);

    $periodo = PeriodoAcademico::factory()->create(['activo' => true]);
    $asignacion = Asignacion::factory()->create(['periodo_academico_id' => $periodo->id]);
    Matricula::factory()->create([
        'alumno_id' => $alumno->id,
        'asignacion_id' => $asignacion->id,
    ]);

    $actividadOculta = Actividad::factory()->create([
        'asignacion_id' => $asignacion->id,
        'titulo' => 'Evaluacion Interna Secreta',
    ]);

    Nota::factory()->create([
        'alumno_id' => $alumno->id,
        'actividad_id' => $actividadOculta->id,
        'visible_para_alumno' => false,
    ]);

    $response = $this->actingAs($padre->user)
        ->get(route('padre.hijos.show', $alumno));

    $response->assertOk();
    $response->assertSee('Evaluacion Interna Secreta');
});
