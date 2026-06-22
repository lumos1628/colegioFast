<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Docente;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DocenteSeeder extends Seeder
{
    public function run(): void
    {
        $docentes = [
            ['name' => 'María García López', 'email' => 'maria.garcia@colegio.com', 'especialidad' => 'Matemática'],
            ['name' => 'Juan Pérez Rodríguez', 'email' => 'juan.perez@colegio.com', 'especialidad' => 'Comunicación'],
            ['name' => 'Ana Martínez Sánchez', 'email' => 'ana.martinez@colegio.com', 'especialidad' => 'Ciencias'],
            ['name' => 'Carlos López Hernández', 'email' => 'carlos.lopez@colegio.com', 'especialidad' => 'Historia'],
            ['name' => 'Laura Rodríguez Gómez', 'email' => 'laura.rodriguez@colegio.com', 'especialidad' => 'Inglés'],
        ];

        foreach ($docentes as $docente) {
            $user = User::factory()->create([
                'name' => $docente['name'],
                'email' => $docente['email'],
                'role' => UserRole::Docente,
                'password' => Hash::make('password'),
            ]);

            Docente::create([
                'user_id' => $user->id,
                'especialidad' => $docente['especialidad'],
                'telefono' => fake()->numerify('9########'),
            ]);
        }
    }
}
