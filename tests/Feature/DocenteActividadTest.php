<?php

use App\Enums\Calificacion;
use App\Models\Actividad;
use App\Models\Alumno;
use App\Models\Asignacion;
use App\Models\Capacidad;
use App\Models\Competencia;
use App\Models\Docente;
use App\Models\Matricula;
use App\Models\Nota;

test('docente puede ver lista de actividades del curso', function () {
    $docente = Docente::factory()->create();
    $asignacion = Asignacion::factory()->create(['docente_id' => $docente->id]);
    $actividad = Actividad::factory()->create(['asignacion_id' => $asignacion->id]);

    $response = $this->actingAs($docente->user)
        ->get(route('docente.cursos.actividades.index', $asignacion));

    $response->assertOk();
    $response->assertSee($actividad->titulo);
    $response->assertSee('Crear actividad');
});

test('docente puede ver formulario de crear actividad', function () {
    $docente = Docente::factory()->create();
    $asignacion = Asignacion::factory()->create(['docente_id' => $docente->id]);

    $response = $this->actingAs($docente->user)
        ->get(route('docente.cursos.actividades.create', $asignacion));

    $response->assertOk();
    $response->assertSee('Crear Actividad');
    $response->assertSee('Título de la actividad');
});

test('docente puede crear actividad', function () {
    $docente = Docente::factory()->create();
    $asignacion = Asignacion::factory()->create(['docente_id' => $docente->id]);
    $competencia = Competencia::factory()->create();
    $capacidad = Capacidad::factory()->create(['competencia_id' => $competencia->id]);

    $response = $this->actingAs($docente->user)
        ->post(route('docente.cursos.actividades.store', $asignacion), [
            'titulo' => 'Actividad de prueba',
            'descripcion' => 'Descripción de prueba',
            'fecha' => '2024-06-15',
            'competencia_id' => $competencia->id,
            'capacidad_id' => $capacidad->id,
        ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('actividades', [
        'titulo' => 'Actividad de prueba',
        'asignacion_id' => $asignacion->id,
    ]);
});

test('docente puede ver detalle de actividad con alumnos', function () {
    $docente = Docente::factory()->create();
    $asignacion = Asignacion::factory()->create(['docente_id' => $docente->id]);
    $actividad = Actividad::factory()->create(['asignacion_id' => $asignacion->id]);
    $alumno = Alumno::factory()->create();
    Matricula::factory()->create([
        'asignacion_id' => $asignacion->id,
        'alumno_id' => $alumno->id,
    ]);

    $response = $this->actingAs($docente->user)
        ->get(route('docente.cursos.actividades.show', [$asignacion, $actividad]));

    $response->assertOk();
    $response->assertSee($actividad->titulo);
    $response->assertSee($alumno->nombres);
    $response->assertSee('Calificaciones');
});

test('docente puede registrar calificaciones en lote', function () {
    $docente = Docente::factory()->create();
    $asignacion = Asignacion::factory()->create(['docente_id' => $docente->id]);
    $actividad = Actividad::factory()->create(['asignacion_id' => $asignacion->id]);
    $alumno1 = Alumno::factory()->create();
    $alumno2 = Alumno::factory()->create();
    Matricula::factory()->create(['asignacion_id' => $asignacion->id, 'alumno_id' => $alumno1->id]);
    Matricula::factory()->create(['asignacion_id' => $asignacion->id, 'alumno_id' => $alumno2->id]);

    $response = $this->actingAs($docente->user)
        ->post(route('docente.cursos.actividades.notas.store', [$asignacion, $actividad]), [
            'notas' => [
                ['alumno_id' => $alumno1->id, 'calificacion' => 'AD', 'observacion' => 'Excelente'],
                ['alumno_id' => $alumno2->id, 'calificacion' => 'B', 'observacion' => null],
            ],
        ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('notas', [
        'actividad_id' => $actividad->id,
        'alumno_id' => $alumno1->id,
        'calificacion' => Calificacion::AD->value,
        'observacion' => 'Excelente',
    ]);
    $this->assertDatabaseHas('notas', [
        'actividad_id' => $actividad->id,
        'alumno_id' => $alumno2->id,
        'calificacion' => Calificacion::B->value,
    ]);
});

test('calificaciones se actualizan si ya existen', function () {
    $docente = Docente::factory()->create();
    $asignacion = Asignacion::factory()->create(['docente_id' => $docente->id]);
    $actividad = Actividad::factory()->create(['asignacion_id' => $asignacion->id]);
    $alumno = Alumno::factory()->create();
    Matricula::factory()->create(['asignacion_id' => $asignacion->id, 'alumno_id' => $alumno->id]);

    Nota::factory()->create([
        'actividad_id' => $actividad->id,
        'alumno_id' => $alumno->id,
        'calificacion' => Calificacion::C,
    ]);

    $response = $this->actingAs($docente->user)
        ->post(route('docente.cursos.actividades.notas.store', [$asignacion, $actividad]), [
            'notas' => [
                ['alumno_id' => $alumno->id, 'calificacion' => 'A', 'observacion' => 'Mejoró'],
            ],
        ]);

    $response->assertRedirect();
    $this->assertDatabaseCount('notas', 1);
    $this->assertDatabaseHas('notas', [
        'actividad_id' => $actividad->id,
        'alumno_id' => $alumno->id,
        'calificacion' => Calificacion::A->value,
        'observacion' => 'Mejoró',
    ]);
});

test('docente no puede crear actividad en curso de otro docente', function () {
    $docente1 = Docente::factory()->create();
    $docente2 = Docente::factory()->create();
    $asignacion = Asignacion::factory()->create(['docente_id' => $docente2->id]);
    $competencia = Competencia::factory()->create();
    $capacidad = Capacidad::factory()->create(['competencia_id' => $competencia->id]);

    $response = $this->actingAs($docente1->user)
        ->post(route('docente.cursos.actividades.store', $asignacion), [
            'titulo' => 'Actividad intrusa',
            'fecha' => '2024-06-15',
            'competencia_id' => $competencia->id,
            'capacidad_id' => $capacidad->id,
        ]);

    $response->assertForbidden();
});

test('docente no puede ver actividades de curso de otro docente', function () {
    $docente1 = Docente::factory()->create();
    $docente2 = Docente::factory()->create();
    $asignacion = Asignacion::factory()->create(['docente_id' => $docente2->id]);

    $response = $this->actingAs($docente1->user)
        ->get(route('docente.cursos.actividades.index', $asignacion));

    $response->assertForbidden();
});

test('vista de curso incluye link a actividades', function () {
    $docente = Docente::factory()->create();
    $asignacion = Asignacion::factory()->create(['docente_id' => $docente->id]);

    $response = $this->actingAs($docente->user)
        ->get(route('docente.cursos.show', $asignacion));

    $response->assertOk();
    $response->assertSee('Gestionar actividades');
});
