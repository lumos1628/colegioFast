<?php

namespace Database\Seeders;

use App\Enums\IncidenciaTipo;
use App\Models\Alumno;
use App\Models\Docente;
use App\Models\IncidenciaConducta;
use Illuminate\Database\Seeder;

class IncidenciaConductaSeeder extends Seeder
{
    public function run(): void
    {
        $alumnos = Alumno::all();
        $docentes = Docente::all();

        for ($i = 0; $i < 30; $i++) {
            IncidenciaConducta::create([
                'alumno_id' => $alumnos->random()->id,
                'docente_id' => $docentes->random()->id,
                'tipo' => fake()->randomElement(IncidenciaTipo::cases()),
                'descripcion' => fake()->paragraph(),
                'fecha' => fake()->dateTimeBetween('-3 months', 'now'),
            ]);
        }
    }
}
