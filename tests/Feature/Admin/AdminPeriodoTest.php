<?php

use App\Enums\UserRole;
use App\Models\PeriodoAcademico;
use App\Models\User;

test('admin puede crear periodo academico', function () {
    $user = User::factory()->create(['role' => UserRole::Admin]);

    $response = $this->actingAs($user)
        ->post(route('admin.periodos.store'), [
            'nombre' => 'Bimestre I',
            'fecha_inicio' => '2026-03-01',
            'fecha_fin' => '2026-06-30',
            'anio_escolar' => 2026,
        ]);

    $response->assertRedirect(route('admin.periodos.index'));
    $this->assertDatabaseHas('periodo_academicos', [
        'nombre' => 'Bimestre I',
        'anio_escolar' => 2026,
    ]);
});

test('admin puede activar periodo y desactivar otros', function () {
    $user = User::factory()->create(['role' => UserRole::Admin]);
    $periodo1 = PeriodoAcademico::factory()->create(['activo' => true]);
    $periodo2 = PeriodoAcademico::factory()->create(['activo' => false]);

    $response = $this->actingAs($user)
        ->post(route('admin.periodos.activar', $periodo2));

    $response->assertRedirect(route('admin.periodos.index'));
    $this->assertDatabaseHas('periodo_academicos', [
        'id' => $periodo1->id,
        'activo' => false,
    ]);
    $this->assertDatabaseHas('periodo_academicos', [
        'id' => $periodo2->id,
        'activo' => true,
    ]);
});

test('admin no puede eliminar periodo activo', function () {
    $user = User::factory()->create(['role' => UserRole::Admin]);
    $periodo = PeriodoAcademico::factory()->create(['activo' => true]);

    $response = $this->actingAs($user)
        ->delete(route('admin.periodos.destroy', $periodo));

    $response->assertRedirect(route('admin.periodos.index'));
    $response->assertSessionHas('error');
    $this->assertDatabaseHas('periodo_academicos', [
        'id' => $periodo->id,
    ]);
});

test('admin puede eliminar periodo inactivo', function () {
    $user = User::factory()->create(['role' => UserRole::Admin]);
    $periodo = PeriodoAcademico::factory()->create(['activo' => false]);

    $response = $this->actingAs($user)
        ->delete(route('admin.periodos.destroy', $periodo));

    $response->assertRedirect(route('admin.periodos.index'));
    $this->assertDatabaseMissing('periodo_academicos', [
        'id' => $periodo->id,
    ]);
});
