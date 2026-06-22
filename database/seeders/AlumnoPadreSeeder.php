<?php

namespace Database\Seeders;

use App\Models\Alumno;
use App\Models\Padre;
use Illuminate\Database\Seeder;

class AlumnoPadreSeeder extends Seeder
{
    public function run(): void
    {
        $alumnos = Alumno::all();
        $padres = Padre::all();

        foreach ($alumnos as $alumno) {
            $cantidadPadres = rand(1, 2);
            $padresAsignados = $padres->random(min($cantidadPadres, $padres->count()));

            foreach ($padresAsignados as $padre) {
                $alumno->padres()->attach($padre->id, [
                    'parentesco' => fake()->randomElement(['padre', 'madre', 'tutor']),
                ]);
            }
        }
    }
}
