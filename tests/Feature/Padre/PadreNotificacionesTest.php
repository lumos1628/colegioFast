<?php

use App\Enums\NotificacionTipo;
use App\Models\Notificacion;
use App\Models\Padre;

test('padre puede ver lista de notificaciones', function () {
    $padre = Padre::factory()->create();
    Notificacion::factory()->create([
        'user_id' => $padre->user->id,
        'tipo' => NotificacionTipo::Inasistencia,
        'mensaje' => 'Tu hijo faltó hoy',
    ]);

    $response = $this->actingAs($padre->user)
        ->get(route('padre.notificaciones'));

    $response->assertOk();
    $response->assertSee('Tu hijo faltó hoy');
});

test('padre puede filtrar notificaciones no leidas', function () {
    $padre = Padre::factory()->create();
    Notificacion::factory()->create([
        'user_id' => $padre->user->id,
        'leido' => false,
        'mensaje' => 'Notificacion sin leer',
    ]);
    Notificacion::factory()->create([
        'user_id' => $padre->user->id,
        'leido' => true,
        'mensaje' => 'Notificacion leida',
    ]);

    $response = $this->actingAs($padre->user)
        ->get(route('padre.notificaciones', ['filtro' => 'no_leidas']));

    $response->assertOk();
    $response->assertSee('Notificacion sin leer');
    $response->assertDontSee('Notificacion leida');
});

test('padre puede marcar notificacion como leida', function () {
    $padre = Padre::factory()->create();
    $notificacion = Notificacion::factory()->create([
        'user_id' => $padre->user->id,
        'leido' => false,
    ]);

    $response = $this->actingAs($padre->user)
        ->post(route('padre.notificaciones.leida', $notificacion));

    $response->assertRedirect();
    $this->assertDatabaseHas('notificaciones', [
        'id' => $notificacion->id,
        'leido' => true,
    ]);
});

test('padre no puede marcar notificacion de otro usuario', function () {
    $padre = Padre::factory()->create();
    $otroPadre = Padre::factory()->create();
    $notificacion = Notificacion::factory()->create([
        'user_id' => $otroPadre->user->id,
        'leido' => false,
    ]);

    $response = $this->actingAs($padre->user)
        ->post(route('padre.notificaciones.leida', $notificacion));

    $response->assertForbidden();
});
