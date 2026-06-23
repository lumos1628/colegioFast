<?php

use App\Enums\AsistenciaEstado;
use App\Enums\Calificacion;
use App\Enums\IncidenciaTipo;
use App\Models\Actividad;
use App\Models\Alumno;
use App\Models\Asignacion;
use App\Models\Asistencia;
use App\Models\Docente;
use App\Models\IncidenciaConducta;
use App\Models\Matricula;
use App\Models\Nota;

test('docente puede ver ficha de alumno matriculado', function () {
    $docente = Docente::factory()->create();
    $asignacion = Asignacion::factory()->create(['docente_id' => $docente->id]);
    $alumno = Alumno::factory()->create();
    Matricula::factory()->create([
        'asignacion_id' => $asignacion->id,
        'alumno_id' => $alumno->id,
    ]);

    $response = $this->actingAs($docente->user)
        ->get(route('docente.cursos.alumnos.show', [$asignacion, $alumno]));

    $response->assertOk();
    $response->assertSee($alumno->nombres);
    $response->assertSee($alumno->apellido_paterno);
    $response->assertSee($alumno->dni);
});

test('docente no puede ver ficha de alumno no matriculado en su curso', function () {
    $docente = Docente::factory()->create();
    $asignacion = Asignacion::factory()->create(['docente_id' => $docente->id]);
    $alumno = Alumno::factory()->create();

    $response = $this->actingAs($docente->user)
        ->get(route('docente.cursos.alumnos.show', [$asignacion, $alumno]));

    $response->assertNotFound();
});

test('docente no puede ver ficha de alumno en curso de otro docente', function () {
    $docente1 = Docente::factory()->create();
    $docente2 = Docente::factory()->create();
    $asignacion = Asignacion::factory()->create(['docente_id' => $docente2->id]);
    $alumno = Alumno::factory()->create();
    Matricula::factory()->create([
        'asignacion_id' => $asignacion->id,
        'alumno_id' => $alumno->id,
    ]);

    $response = $this->actingAs($docente1->user)
        ->get(route('docente.cursos.alumnos.show', [$asignacion, $alumno]));

    $response->assertForbidden();
});

test('ficha muestra calificaciones del alumno en el curso', function () {
    $docente = Docente::factory()->create();
    $asignacion = Asignacion::factory()->create(['docente_id' => $docente->id]);
    $alumno = Alumno::factory()->create();
    Matricula::factory()->create([
        'asignacion_id' => $asignacion->id,
        'alumno_id' => $alumno->id,
    ]);

    $actividad = Actividad::factory()->create(['asignacion_id' => $asignacion->id]);
    $nota = Nota::factory()->create([
        'actividad_id' => $actividad->id,
        'alumno_id' => $alumno->id,
        'calificacion' => Calificacion::A,
    ]);

    $response = $this->actingAs($docente->user)
        ->get(route('docente.cursos.alumnos.show', [$asignacion, $alumno]));

    $response->assertOk();
    $response->assertSee($actividad->titulo);
    $response->assertSee($nota->calificacion->label());
});

test('ficha muestra asistencias del alumno en el curso', function () {
    $docente = Docente::factory()->create();
    $asignacion = Asignacion::factory()->create(['docente_id' => $docente->id]);
    $alumno = Alumno::factory()->create();
    Matricula::factory()->create([
        'asignacion_id' => $asignacion->id,
        'alumno_id' => $alumno->id,
    ]);

    $asistencia = Asistencia::factory()->create([
        'alumno_id' => $alumno->id,
        'asignacion_id' => $asignacion->id,
        'estado' => AsistenciaEstado::Presente,
    ]);

    $response = $this->actingAs($docente->user)
        ->get(route('docente.cursos.alumnos.show', [$asignacion, $alumno]));

    $response->assertOk();
    $response->assertSee($asistencia->fecha->format('d/m/Y'));
    $response->assertSee($asistencia->estado->label());
});

test('ficha muestra incidencias de conducta del alumno', function () {
    $docente = Docente::factory()->create();
    $asignacion = Asignacion::factory()->create(['docente_id' => $docente->id]);
    $alumno = Alumno::factory()->create();
    Matricula::factory()->create([
        'asignacion_id' => $asignacion->id,
        'alumno_id' => $alumno->id,
    ]);

    $incidencia = IncidenciaConducta::factory()->create([
        'alumno_id' => $alumno->id,
        'docente_id' => $docente->id,
        'tipo' => IncidenciaTipo::FaltaLeve,
    ]);

    $response = $this->actingAs($docente->user)
        ->get(route('docente.cursos.alumnos.show', [$asignacion, $alumno]));

    $response->assertOk();
    $response->assertSee($incidencia->tipo->label());
    $response->assertSee($incidencia->descripcion);
});

test('vista de curso incluye input de busqueda', function () {
    $docente = Docente::factory()->create();
    $asignacion = Asignacion::factory()->create(['docente_id' => $docente->id]);

    $response = $this->actingAs($docente->user)
        ->get(route('docente.cursos.show', $asignacion));

    $response->assertOk();
    $response->assertSee('buscar-alumno');
    $response->assertSee('Buscar por nombre o DNI');
});
