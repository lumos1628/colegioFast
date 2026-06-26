<?php

use App\Enums\UserRole;
use App\Models\Asignacion;
use App\Models\Curso;
use App\Models\Docente;
use App\Models\Matricula;
use App\Models\PeriodoAcademico;
use App\Models\User;

test('admin puede crear asignacion', function () {
    $user = User::factory()->create(['role' => UserRole::Admin]);
    $docente = Docente::factory()->create();
    $curso = Curso::factory()->create();
    $periodo = PeriodoAcademico::factory()->create();

    $response = $this->actingAs($user)
        ->post(route('admin.asignaciones.store'), [
            'docente_id' => $docente->id,
            'curso_id' => $curso->id,
            'periodo_academico_id' => $periodo->id,
            'dia_semana' => 1,
            'hora_inicio' => '08:00',
            'hora_fin' => '09:30',
        ]);

    $response->assertRedirect(route('admin.asignaciones.index'));
    $this->assertDatabaseHas('asignaciones', [
        'docente_id' => $docente->id,
        'curso_id' => $curso->id,
        'periodo_academico_id' => $periodo->id,
        'dia_semana' => 1,
    ]);
});

test('admin puede editar asignacion', function () {
    $user = User::factory()->create(['role' => UserRole::Admin]);
    $asignacion = Asignacion::factory()->create();
    $nuevoDocente = Docente::factory()->create();

    $response = $this->actingAs($user)
        ->put(route('admin.asignaciones.update', $asignacion), [
            'docente_id' => $nuevoDocente->id,
            'curso_id' => $asignacion->curso_id,
            'periodo_academico_id' => $asignacion->periodo_academico_id,
            'dia_semana' => 2,
            'hora_inicio' => '10:00',
            'hora_fin' => '11:30',
        ]);

    $response->assertRedirect(route('admin.asignaciones.index'));
    $this->assertDatabaseHas('asignaciones', [
        'id' => $asignacion->id,
        'docente_id' => $nuevoDocente->id,
        'dia_semana' => 2,
    ]);
});

test('admin no puede eliminar asignacion con matriculas', function () {
    $user = User::factory()->create(['role' => UserRole::Admin]);
    $asignacion = Asignacion::factory()->create();
    Matricula::factory()->create(['asignacion_id' => $asignacion->id]);

    $response = $this->actingAs($user)
        ->delete(route('admin.asignaciones.destroy', $asignacion));

    $response->assertRedirect(route('admin.asignaciones.index'));
    $response->assertSessionHas('error');
    $this->assertDatabaseHas('asignaciones', [
        'id' => $asignacion->id,
    ]);
});

test('admin puede eliminar asignacion sin matriculas', function () {
    $user = User::factory()->create(['role' => UserRole::Admin]);
    $asignacion = Asignacion::factory()->create();

    $response = $this->actingAs($user)
        ->delete(route('admin.asignaciones.destroy', $asignacion));

    $response->assertRedirect(route('admin.asignaciones.index'));
    $this->assertDatabaseMissing('asignaciones', [
        'id' => $asignacion->id,
    ]);
});
