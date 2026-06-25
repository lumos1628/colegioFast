<?php

use App\Enums\AsistenciaEstado;
use App\Models\Alumno;
use App\Models\Asignacion;
use App\Models\Asistencia;
use App\Models\Docente;
use App\Models\Matricula;

test('docente puede ver formulario de asistencia', function () {
    $docente = Docente::factory()->create();
    $asignacion = Asignacion::factory()->create(['docente_id' => $docente->id]);

    $response = $this->actingAs($docente->user)
        ->get(route('docente.cursos.asistencia.index', $asignacion));

    $response->assertOk();
    $response->assertSee('Registrar Asistencia');
    $response->assertSee('Alumnos');
});

test('formulario muestra alumnos matriculados', function () {
    $docente = Docente::factory()->create();
    $asignacion = Asignacion::factory()->create(['docente_id' => $docente->id]);
    $alumno = Alumno::factory()->create();
    Matricula::factory()->create([
        'asignacion_id' => $asignacion->id,
        'alumno_id' => $alumno->id,
    ]);

    $response = $this->actingAs($docente->user)
        ->get(route('docente.cursos.asistencia.index', $asignacion));

    $response->assertOk();
    $response->assertSee($alumno->nombres);
    $response->assertSee($alumno->apellido_paterno);
});

test('docente puede registrar asistencia en lote', function () {
    $docente = Docente::factory()->create();
    $asignacion = Asignacion::factory()->create(['docente_id' => $docente->id]);
    $alumno1 = Alumno::factory()->create();
    $alumno2 = Alumno::factory()->create();
    Matricula::factory()->create(['asignacion_id' => $asignacion->id, 'alumno_id' => $alumno1->id]);
    Matricula::factory()->create(['asignacion_id' => $asignacion->id, 'alumno_id' => $alumno2->id]);

    $fecha = '2024-06-15';

    $response = $this->actingAs($docente->user)
        ->post(route('docente.cursos.asistencia.store', $asignacion), [
            'fecha' => $fecha,
            'asistencias' => [
                ['alumno_id' => $alumno1->id, 'estado' => 'presente', 'observacion' => null],
                ['alumno_id' => $alumno2->id, 'estado' => 'tardanza', 'observacion' => 'Llegó 10 min tarde'],
            ],
        ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('asistencias', [
        'asignacion_id' => $asignacion->id,
        'alumno_id' => $alumno1->id,
        'fecha' => $fecha,
        'estado' => AsistenciaEstado::Presente->value,
    ]);
    $this->assertDatabaseHas('asistencias', [
        'asignacion_id' => $asignacion->id,
        'alumno_id' => $alumno2->id,
        'fecha' => $fecha,
        'estado' => AsistenciaEstado::Tardanza->value,
        'observacion' => 'Llegó 10 min tarde',
    ]);
});

test('asistencia se actualiza si ya existe para esa fecha', function () {
    $docente = Docente::factory()->create();
    $asignacion = Asignacion::factory()->create(['docente_id' => $docente->id]);
    $alumno = Alumno::factory()->create();
    Matricula::factory()->create(['asignacion_id' => $asignacion->id, 'alumno_id' => $alumno->id]);

    $fecha = '2024-06-15';

    Asistencia::factory()->create([
        'asignacion_id' => $asignacion->id,
        'alumno_id' => $alumno->id,
        'fecha' => $fecha,
        'estado' => AsistenciaEstado::Presente,
    ]);

    $response = $this->actingAs($docente->user)
        ->post(route('docente.cursos.asistencia.store', $asignacion), [
            'fecha' => $fecha,
            'asistencias' => [
                ['alumno_id' => $alumno->id, 'estado' => 'ausente', 'observacion' => 'No vino'],
            ],
        ]);

    $response->assertRedirect();
    $this->assertDatabaseCount('asistencias', 1);
    $this->assertDatabaseHas('asistencias', [
        'asignacion_id' => $asignacion->id,
        'alumno_id' => $alumno->id,
        'fecha' => $fecha,
        'estado' => AsistenciaEstado::Ausente->value,
        'observacion' => 'No vino',
    ]);
});

test('docente puede ver asistencia de fecha específica', function () {
    $docente = Docente::factory()->create();
    $asignacion = Asignacion::factory()->create(['docente_id' => $docente->id]);
    $alumno = Alumno::factory()->create();
    Matricula::factory()->create(['asignacion_id' => $asignacion->id, 'alumno_id' => $alumno->id]);

    $fecha = '2024-06-15';

    Asistencia::factory()->create([
        'asignacion_id' => $asignacion->id,
        'alumno_id' => $alumno->id,
        'fecha' => $fecha,
        'estado' => AsistenciaEstado::Tardanza,
    ]);

    $response = $this->actingAs($docente->user)
        ->get(route('docente.cursos.asistencia.index', [$asignacion, 'fecha' => $fecha]));

    $response->assertOk();
    $response->assertSee($fecha);
});

test('formulario carga estados ya registrados', function () {
    $docente = Docente::factory()->create();
    $asignacion = Asignacion::factory()->create(['docente_id' => $docente->id]);
    $alumno = Alumno::factory()->create();
    Matricula::factory()->create(['asignacion_id' => $asignacion->id, 'alumno_id' => $alumno->id]);

    $fecha = now()->format('Y-m-d');

    Asistencia::factory()->create([
        'asignacion_id' => $asignacion->id,
        'alumno_id' => $alumno->id,
        'fecha' => $fecha,
        'estado' => AsistenciaEstado::Justificado,
        'observacion' => 'Cita médica',
    ]);

    $response = $this->actingAs($docente->user)
        ->get(route('docente.cursos.asistencia.index', $asignacion));

    $response->assertOk();
    $response->assertSee('Cita médica');
});

test('docente no puede registrar asistencia en curso de otro docente', function () {
    $docente1 = Docente::factory()->create();
    $docente2 = Docente::factory()->create();
    $asignacion = Asignacion::factory()->create(['docente_id' => $docente2->id]);
    $alumno = Alumno::factory()->create();
    Matricula::factory()->create(['asignacion_id' => $asignacion->id, 'alumno_id' => $alumno->id]);

    $response = $this->actingAs($docente1->user)
        ->post(route('docente.cursos.asistencia.store', $asignacion), [
            'fecha' => '2024-06-15',
            'asistencias' => [
                ['alumno_id' => $alumno->id, 'estado' => 'presente'],
            ],
        ]);

    $response->assertForbidden();
});

test('docente no puede ver asistencia de curso de otro docente', function () {
    $docente1 = Docente::factory()->create();
    $docente2 = Docente::factory()->create();
    $asignacion = Asignacion::factory()->create(['docente_id' => $docente2->id]);

    $response = $this->actingAs($docente1->user)
        ->get(route('docente.cursos.asistencia.index', $asignacion));

    $response->assertForbidden();
});

test('vista de curso incluye link a asistencia', function () {
    $docente = Docente::factory()->create();
    $asignacion = Asignacion::factory()->create(['docente_id' => $docente->id]);

    $response = $this->actingAs($docente->user)
        ->get(route('docente.cursos.show', $asignacion));

    $response->assertOk();
    $response->assertSee('Asistencia');
});
