<?php

use App\Enums\UserRole;
use App\Models\Alumno;
use App\Models\User;

test('admin puede ver lista de alumnos', function () {
    $user = User::factory()->create(['role' => UserRole::Admin]);
    Alumno::factory()->create();

    $response = $this->actingAs($user)
        ->get(route('admin.alumnos.index'));

    $response->assertOk();
    $response->assertSee('Gestión de Alumnos');
});

test('admin puede crear alumno con usuario', function () {
    $user = User::factory()->create(['role' => UserRole::Admin]);

    $response = $this->actingAs($user)
        ->post(route('admin.alumnos.store'), [
            'nombres' => 'Juan',
            'apellido_paterno' => 'Pérez',
            'apellido_materno' => 'García',
            'fecha_nacimiento' => '2015-03-15',
            'dni' => '12345678',
            'grado' => 1,
            'seccion' => 'A',
            'email' => 'juan@colegio.com',
        ]);

    $response->assertRedirect(route('admin.alumnos.index'));
    $this->assertDatabaseHas('alumnos', [
        'nombres' => 'Juan',
        'apellido_paterno' => 'Pérez',
        'dni' => '12345678',
    ]);
    $this->assertDatabaseHas('users', [
        'email' => 'juan@colegio.com',
        'role' => UserRole::Alumno->value,
    ]);
});

test('admin puede editar alumno', function () {
    $user = User::factory()->create(['role' => UserRole::Admin]);
    $alumno = Alumno::factory()->create();

    $response = $this->actingAs($user)
        ->put(route('admin.alumnos.update', $alumno), [
            'nombres' => 'María',
            'apellido_paterno' => 'López',
            'apellido_materno' => 'Torres',
            'fecha_nacimiento' => '2014-05-20',
            'dni' => $alumno->dni,
            'grado' => 2,
            'seccion' => 'B',
        ]);

    $response->assertRedirect(route('admin.alumnos.index'));
    $this->assertDatabaseHas('alumnos', [
        'id' => $alumno->id,
        'nombres' => 'María',
        'grado' => 2,
    ]);
});

test('admin puede eliminar alumno y su usuario', function () {
    $user = User::factory()->create(['role' => UserRole::Admin]);
    $alumno = Alumno::factory()->create();
    $userId = $alumno->user_id;

    $response = $this->actingAs($user)
        ->delete(route('admin.alumnos.destroy', $alumno));

    $response->assertRedirect(route('admin.alumnos.index'));
    $this->assertDatabaseMissing('alumnos', ['id' => $alumno->id]);
    $this->assertDatabaseMissing('users', ['id' => $userId]);
});
