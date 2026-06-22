<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Alumno;
use App\Models\BitacoraPsicologica;
use App\Models\User;
use Illuminate\Database\Seeder;

class BitacoraPsicologicaSeeder extends Seeder
{
    public function run(): void
    {
        $psicologoUser = User::where('role', UserRole::Psicologo)->first();

        if (! $psicologoUser) {
            return;
        }

        $alumnos = Alumno::all();

        for ($i = 0; $i < 10; $i++) {
            BitacoraPsicologica::create([
                'alumno_id' => $alumnos->random()->id,
                'psicologo_id' => $psicologoUser->id,
                'fecha' => fake()->dateTimeBetween('-3 months', 'now'),
                'observaciones' => fake()->paragraphs(3, true),
            ]);
        }
    }
}
