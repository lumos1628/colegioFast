<?php

use App\Enums\Calificacion;
use App\Models\Actividad;
use App\Models\Alumno;
use App\Models\Asignacion;
use App\Models\Matricula;
use App\Models\Nota;
use App\Models\PeriodoAcademico;

test('alumno autenticado puede ver su dashboard', function () {
    $alumno = Alumno::factory()->create();

    $response = $this->actingAs($alumno->user)
        ->get(route('alumno.dashboard'));

    $response->assertOk();
    $response->assertSee('Mis Cursos');
});

test('alumno ve cursos matriculados del periodo activo', function () {
    $alumno = Alumno::factory()->create();
    $periodo = PeriodoAcademico::factory()->create(['activo' => true]);
    $asignacion = Asignacion::factory()->create(['periodo_academico_id' => $periodo->id]);
    Matricula::factory()->create([
        'alumno_id' => $alumno->id,
        'asignacion_id' => $asignacion->id,
    ]);

    $response = $this->actingAs($alumno->user)
        ->get(route('alumno.dashboard'));

    $response->assertOk();
    $response->assertSee($asignacion->curso->nombre);
});

test('alumno no ve cursos de periodos cerrados', function () {
    $alumno = Alumno::factory()->create();
    $periodo = PeriodoAcademico::factory()->create(['activo' => false]);
    $asignacion = Asignacion::factory()->create(['periodo_academico_id' => $periodo->id]);
    Matricula::factory()->create([
        'alumno_id' => $alumno->id,
        'asignacion_id' => $asignacion->id,
    ]);

    $response = $this->actingAs($alumno->user)
        ->get(route('alumno.dashboard'));

    $response->assertOk();
    $response->assertDontSee($asignacion->curso->nombre);
});

test('alumno puede ver detalle de curso matriculado', function () {
    $alumno = Alumno::factory()->create();
    $periodo = PeriodoAcademico::factory()->create(['activo' => true]);
    $asignacion = Asignacion::factory()->create(['periodo_academico_id' => $periodo->id]);
    Matricula::factory()->create([
        'alumno_id' => $alumno->id,
        'asignacion_id' => $asignacion->id,
    ]);

    $response = $this->actingAs($alumno->user)
        ->get(route('alumno.cursos.show', $asignacion));

    $response->assertOk();
    $response->assertSee($asignacion->curso->nombre);
});

test('alumno no puede acceder a curso sin matricula', function () {
    $alumno = Alumno::factory()->create();
    $asignacion = Asignacion::factory()->create();

    $response = $this->actingAs($alumno->user)
        ->get(route('alumno.cursos.show', $asignacion));

    $response->assertForbidden();
});

test('alumno no ve notas con visible_para_alumno false', function () {
    $alumno = Alumno::factory()->create();
    $periodo = PeriodoAcademico::factory()->create(['activo' => true]);
    $asignacion = Asignacion::factory()->create(['periodo_academico_id' => $periodo->id]);
    Matricula::factory()->create([
        'alumno_id' => $alumno->id,
        'asignacion_id' => $asignacion->id,
    ]);

    $actividadVisible = Actividad::factory()->create([
        'asignacion_id' => $asignacion->id,
        'titulo' => 'Examen Parcial',
    ]);
    $actividadOculta = Actividad::factory()->create([
        'asignacion_id' => $asignacion->id,
        'titulo' => 'Evaluacion Interna Secreta',
    ]);

    Nota::factory()->create([
        'alumno_id' => $alumno->id,
        'actividad_id' => $actividadVisible->id,
        'visible_para_alumno' => true,
        'calificacion' => Calificacion::A,
    ]);
    Nota::factory()->create([
        'alumno_id' => $alumno->id,
        'actividad_id' => $actividadOculta->id,
        'visible_para_alumno' => false,
        'calificacion' => Calificacion::C,
    ]);

    $response = $this->actingAs($alumno->user)
        ->get(route('alumno.cursos.show', $asignacion));

    $response->assertOk();
    $response->assertSee('Examen Parcial');
    $response->assertSee('Evaluacion Interna Secreta');
    $response->assertSee('Sin calificar');
});
