<?php

use App\Models\Alumno;
use App\Models\Asignacion;
use App\Models\Docente;
use App\Models\Matricula;

test('docente puede descargar reporte de su curso', function () {
    $docente = Docente::factory()->create();
    $asignacion = Asignacion::factory()->create(['docente_id' => $docente->id]);
    $alumno = Alumno::factory()->create();
    Matricula::factory()->create([
        'alumno_id' => $alumno->id,
        'asignacion_id' => $asignacion->id,
    ]);

    $response = $this->actingAs($docente->user)
        ->get(route('docente.cursos.reporte', $asignacion));

    $response->assertOk();
    $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
});

test('docente no puede descargar reporte de curso de otro docente', function () {
    $docente1 = Docente::factory()->create();
    $docente2 = Docente::factory()->create();
    $asignacion = Asignacion::factory()->create(['docente_id' => $docente2->id]);

    $response = $this->actingAs($docente1->user)
        ->get(route('docente.cursos.reporte', $asignacion));

    $response->assertForbidden();
});
