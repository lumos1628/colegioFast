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
        $padres = Padre::all()->keyBy('id');
        $padreIds = $padres->keys()->all();

        foreach ($alumnos as $index => $alumno) {
            $padreId = $padreIds[$index % count($padreIds)];
            $parentesco = fake()->randomElement(['padre', 'madre', 'tutor']);

            $alumno->padres()->attach($padreId, [
                'parentesco' => $parentesco,
            ]);
        }
    }
}
