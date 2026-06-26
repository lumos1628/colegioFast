<?php

use App\Models\Alumno;
use App\Models\Padre;
use App\Models\Pago;
use App\Models\PeriodoAcademico;

test('padre puede ver estado financiero', function () {
    $padre = Padre::factory()->create();
    $alumno = Alumno::factory()->create();
    $padre->alumnos()->attach($alumno->id, ['parentesco' => 'padre']);
    $periodo = PeriodoAcademico::factory()->create();
    Pago::factory()->create([
        'alumno_id' => $alumno->id,
        'periodo_academico_id' => $periodo->id,
        'concepto' => 'Pension mensual',
        'monto' => 350.00,
    ]);

    $response = $this->actingAs($padre->user)
        ->get(route('padre.pagos'));

    $response->assertOk();
    $response->assertSee('Estado Financiero');
    $response->assertSee('Pension mensual');
});

test('padre ve pagos de todos sus hijos', function () {
    $padre = Padre::factory()->create();
    $alumno1 = Alumno::factory()->create();
    $alumno2 = Alumno::factory()->create();
    $padre->alumnos()->attach([$alumno1->id, $alumno2->id], ['parentesco' => 'padre']);
    $periodo = PeriodoAcademico::factory()->create();

    Pago::factory()->create([
        'alumno_id' => $alumno1->id,
        'periodo_academico_id' => $periodo->id,
        'concepto' => 'Pago hijo 1',
    ]);
    Pago::factory()->create([
        'alumno_id' => $alumno2->id,
        'periodo_academico_id' => $periodo->id,
        'concepto' => 'Pago hijo 2',
    ]);

    $response = $this->actingAs($padre->user)
        ->get(route('padre.pagos'));

    $response->assertOk();
    $response->assertSee('Pago hijo 1');
    $response->assertSee('Pago hijo 2');
});
