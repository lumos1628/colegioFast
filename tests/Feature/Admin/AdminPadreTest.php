<?php

use App\Enums\UserRole;
use App\Models\Alumno;
use App\Models\Padre;
use App\Models\User;

test('admin puede crear padre con usuario', function () {
    $user = User::factory()->create(['role' => UserRole::Admin]);

    $response = $this->actingAs($user)
        ->post(route('admin.padres.store'), [
            'nombres' => 'Roberto',
            'apellido_paterno' => 'García',
            'apellido_materno' => 'López',
            'dni' => '87654321',
            'telefono' => '999888777',
            'direccion' => 'Av. Principal 123',
            'email' => 'roberto@colegio.com',
        ]);

    $response->assertRedirect(route('admin.padres.index'));
    $this->assertDatabaseHas('padres', [
        'nombres' => 'Roberto',
        'dni' => '87654321',
    ]);
    $this->assertDatabaseHas('users', [
        'email' => 'roberto@colegio.com',
        'role' => UserRole::Padre->value,
    ]);
});

test('admin puede vincular padre a alumno', function () {
    $user = User::factory()->create(['role' => UserRole::Admin]);
    $alumno = Alumno::factory()->create();
    $padre = Padre::factory()->create();

    $response = $this->actingAs($user)
        ->post(route('admin.alumno-padre.store', $alumno), [
            'padre_id' => $padre->id,
            'parentesco' => 'padre',
        ]);

    $response->assertRedirect(route('admin.alumno-padre.index', $alumno));
    $this->assertDatabaseHas('alumno_padre', [
        'alumno_id' => $alumno->id,
        'padre_id' => $padre->id,
        'parentesco' => 'padre',
    ]);
});

test('admin puede desvincular padre de alumno', function () {
    $user = User::factory()->create(['role' => UserRole::Admin]);
    $alumno = Alumno::factory()->create();
    $padre = Padre::factory()->create();
    $alumno->padres()->attach($padre->id, ['parentesco' => 'madre']);

    $response = $this->actingAs($user)
        ->delete(route('admin.alumno-padre.destroy', [$alumno, $padre]));

    $response->assertRedirect(route('admin.alumno-padre.index', $alumno));
    $this->assertDatabaseMissing('alumno_padre', [
        'alumno_id' => $alumno->id,
        'padre_id' => $padre->id,
    ]);
});

test('no se puede vincular padre ya vinculado', function () {
    $user = User::factory()->create(['role' => UserRole::Admin]);
    $alumno = Alumno::factory()->create();
    $padre = Padre::factory()->create();
    $alumno->padres()->attach($padre->id, ['parentesco' => 'padre']);

    $response = $this->actingAs($user)
        ->post(route('admin.alumno-padre.store', $alumno), [
            'padre_id' => $padre->id,
            'parentesco' => 'tutor',
        ]);

    $response->assertRedirect();
    $response->assertSessionHas('warning');
});
