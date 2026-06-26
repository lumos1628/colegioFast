<?php

use App\Enums\UserRole;
use App\Models\Alumno;
use App\Models\BitacoraPsicologica;
use App\Models\User;

test('psicologo puede ver su dashboard', function () {
    $user = User::factory()->create(['role' => UserRole::Psicologo]);

    $response = $this->actingAs($user)
        ->get(route('psicologo.dashboard'));

    $response->assertOk();
    $response->assertSee('Bitácora Psicológica');
});

test('psicologo puede crear bitacora', function () {
    $user = User::factory()->create(['role' => UserRole::Psicologo]);
    $alumno = Alumno::factory()->create();

    $response = $this->actingAs($user)
        ->post(route('psicologo.bitacoras.store'), [
            'alumno_id' => $alumno->id,
            'fecha' => '2026-06-25',
            'observaciones' => 'El alumno muestra progreso en su desarrollo emocional. Se recomienda continuar con las sesiones quincenales.',
        ]);

    $response->assertRedirect(route('psicologo.bitacoras.index'));
    $this->assertDatabaseHas('bitacora_psicologica', [
        'alumno_id' => $alumno->id,
        'psicologo_id' => $user->id,
    ]);
});

test('psicologo puede editar su bitacora', function () {
    $user = User::factory()->create(['role' => UserRole::Psicologo]);
    $alumno = Alumno::factory()->create();
    $bitacora = BitacoraPsicologica::factory()->create([
        'psicologo_id' => $user->id,
        'alumno_id' => $alumno->id,
    ]);

    $response = $this->actingAs($user)
        ->put(route('psicologo.bitacoras.update', $bitacora), [
            'alumno_id' => $alumno->id,
            'fecha' => '2026-06-25',
            'observaciones' => 'Observaciones actualizadas con más detalle sobre el progreso del alumno.',
        ]);

    $response->assertRedirect(route('psicologo.bitacoras.index'));
    $this->assertDatabaseHas('bitacora_psicologica', [
        'id' => $bitacora->id,
        'observaciones' => 'Observaciones actualizadas con más detalle sobre el progreso del alumno.',
    ]);
});

test('psicologo puede eliminar su bitacora', function () {
    $user = User::factory()->create(['role' => UserRole::Psicologo]);
    $alumno = Alumno::factory()->create();
    $bitacora = BitacoraPsicologica::factory()->create([
        'psicologo_id' => $user->id,
        'alumno_id' => $alumno->id,
    ]);

    $response = $this->actingAs($user)
        ->delete(route('psicologo.bitacoras.destroy', $bitacora));

    $response->assertRedirect(route('psicologo.bitacoras.index'));
    $this->assertDatabaseMissing('bitacora_psicologica', [
        'id' => $bitacora->id,
    ]);
});

test('psicologo no puede editar bitacora de otro psicologo', function () {
    $user = User::factory()->create(['role' => UserRole::Psicologo]);
    $otroPsicologo = User::factory()->create(['role' => UserRole::Psicologo]);
    $alumno = Alumno::factory()->create();
    $bitacora = BitacoraPsicologica::factory()->create([
        'psicologo_id' => $otroPsicologo->id,
        'alumno_id' => $alumno->id,
    ]);

    $response = $this->actingAs($user)
        ->put(route('psicologo.bitacoras.update', $bitacora), [
            'alumno_id' => $alumno->id,
            'fecha' => '2026-06-25',
            'observaciones' => 'Intento de edición no autorizado',
        ]);

    $response->assertForbidden();
});

test('psicologo no puede ver bitacora de otro psicologo', function () {
    $user = User::factory()->create(['role' => UserRole::Psicologo]);
    $otroPsicologo = User::factory()->create(['role' => UserRole::Psicologo]);
    $alumno = Alumno::factory()->create();
    $bitacora = BitacoraPsicologica::factory()->create([
        'psicologo_id' => $otroPsicologo->id,
        'alumno_id' => $alumno->id,
    ]);

    $response = $this->actingAs($user)
        ->delete(route('psicologo.bitacoras.destroy', $bitacora));

    $response->assertForbidden();
});
