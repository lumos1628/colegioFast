<?php

use App\Models\Alumno;
use App\Models\Asignacion;
use App\Models\Docente;
use App\Models\Padre;

test('alumno sin modelo docente no puede ver cursos de docente', function () {
    $alumno = Alumno::factory()->create();
    $asignacion = Asignacion::factory()->create();

    $response = $this->actingAs($alumno->user)
        ->get(route('docente.cursos.show', $asignacion));

    $response->assertForbidden();
});

test('padre no puede ver detalle de curso de docente', function () {
    $padre = Padre::factory()->create();
    $asignacion = Asignacion::factory()->create();

    $response = $this->actingAs($padre->user)
        ->get(route('docente.cursos.show', $asignacion));

    $response->assertForbidden();
});

test('docente no puede ver hijos de otro padre', function () {
    $docente = Docente::factory()->create();
    $padre = Padre::factory()->create();
    $alumno = Alumno::factory()->create();
    $padre->alumnos()->attach($alumno->id, ['parentesco' => 'padre']);

    $response = $this->actingAs($docente->user)
        ->get(route('padre.hijos.show', $alumno));

    $response->assertForbidden();
});
