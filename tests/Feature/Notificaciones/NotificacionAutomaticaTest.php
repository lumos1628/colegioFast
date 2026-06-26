<?php

use App\Jobs\EnviarNotificacionJob;
use App\Models\Actividad;
use App\Models\Alumno;
use App\Models\Asignacion;
use App\Models\Docente;
use App\Models\Matricula;
use Illuminate\Support\Facades\Bus;

test('al registrar asistencia ausente se despacha job de notificacion', function () {
    Bus::fake();

    $docente = Docente::factory()->create();
    $asignacion = Asignacion::factory()->create(['docente_id' => $docente->id]);
    $alumno = Alumno::factory()->create();
    Matricula::factory()->create([
        'alumno_id' => $alumno->id,
        'asignacion_id' => $asignacion->id,
    ]);

    $response = $this->actingAs($docente->user)
        ->post(route('docente.cursos.asistencia.store', $asignacion), [
            'fecha' => now()->format('Y-m-d'),
            'asistencias' => [
                ['alumno_id' => $alumno->id, 'estado' => 'ausente'],
            ],
        ]);

    $response->assertRedirect();
    Bus::assertDispatched(EnviarNotificacionJob::class, function ($job) use ($alumno) {
        return $job->alumnoId === $alumno->id && $job->tipo === 'inasistencia';
    });
});

test('al registrar calificacion C se despacha job de notificacion', function () {
    Bus::fake();

    $docente = Docente::factory()->create();
    $asignacion = Asignacion::factory()->create(['docente_id' => $docente->id]);
    $actividad = Actividad::factory()->create(['asignacion_id' => $asignacion->id]);
    $alumno = Alumno::factory()->create();
    Matricula::factory()->create([
        'alumno_id' => $alumno->id,
        'asignacion_id' => $asignacion->id,
    ]);

    $response = $this->actingAs($docente->user)
        ->post(route('docente.cursos.actividades.notas.store', [$asignacion, $actividad]), [
            'notas' => [
                ['alumno_id' => $alumno->id, 'calificacion' => 'C'],
            ],
        ]);

    $response->assertRedirect();
    Bus::assertDispatched(EnviarNotificacionJob::class, function ($job) use ($alumno) {
        return $job->alumnoId === $alumno->id && $job->tipo === 'nota_critica';
    });
});

test('al registrar incidencia se despacha job de notificacion', function () {
    Bus::fake();

    $docente = Docente::factory()->create();
    $asignacion = Asignacion::factory()->create(['docente_id' => $docente->id]);
    $alumno = Alumno::factory()->create();
    Matricula::factory()->create([
        'alumno_id' => $alumno->id,
        'asignacion_id' => $asignacion->id,
    ]);

    $response = $this->actingAs($docente->user)
        ->post(route('docente.cursos.alumnos.incidencias.store', [$asignacion, $alumno]), [
            'tipo' => 'falta_leve',
            'descripcion' => 'Llegó tarde a clase',
            'fecha' => now()->format('Y-m-d'),
        ]);

    $response->assertRedirect();
    Bus::assertDispatched(EnviarNotificacionJob::class, function ($job) use ($alumno) {
        return $job->alumnoId === $alumno->id && $job->tipo === 'incidencia';
    });
});

test('no se despacha job de nota critica para calificaciones aprobatorias', function () {
    Bus::fake();

    $docente = Docente::factory()->create();
    $asignacion = Asignacion::factory()->create(['docente_id' => $docente->id]);
    $actividad = Actividad::factory()->create(['asignacion_id' => $asignacion->id]);
    $alumno = Alumno::factory()->create();
    Matricula::factory()->create([
        'alumno_id' => $alumno->id,
        'asignacion_id' => $asignacion->id,
    ]);

    $response = $this->actingAs($docente->user)
        ->post(route('docente.cursos.actividades.notas.store', [$asignacion, $actividad]), [
            'notas' => [
                ['alumno_id' => $alumno->id, 'calificacion' => 'AD'],
            ],
        ]);

    $response->assertRedirect();
    Bus::assertNotDispatched(EnviarNotificacionJob::class, function ($job) {
        return $job->tipo === 'nota_critica';
    });
});
