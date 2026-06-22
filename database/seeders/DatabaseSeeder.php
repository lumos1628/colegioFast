<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'Admin',      'email' => 'admin@colegio.com',      'role' => 'admin'],
            ['name' => 'Director',   'email' => 'director@colegio.com',   'role' => 'director'],
            ['name' => 'Secretaria', 'email' => 'secretaria@colegio.com', 'role' => 'secretaria'],
            ['name' => 'Docente',    'email' => 'docente@colegio.com',    'role' => 'docente'],
            ['name' => 'Psicólogo',  'email' => 'psicologo@colegio.com',  'role' => 'psicologo'],
            ['name' => 'Alumno',     'email' => 'alumno@colegio.com',     'role' => 'alumno'],
            ['name' => 'Padre',      'email' => 'padre@colegio.com',      'role' => 'padre'],
        ];

        foreach ($users as $user) {
            User::factory()->create([
                'name' => $user['name'],
                'email' => $user['email'],
                'role' => $user['role'],
                'password' => Hash::make('password'),
            ]);
        }

        $this->call([
            RoleProfileSeeder::class,
            PeriodoAcademicoSeeder::class,
            CursoSeeder::class,
            DocenteSeeder::class,
            AlumnoSeeder::class,
            PadreSeeder::class,
            CompetenciaSeeder::class,
            CapacidadSeeder::class,
            AsignacionSeeder::class,
            AlumnoPadreSeeder::class,
            MatriculaSeeder::class,
            ActividadSeeder::class,
            NotaSeeder::class,
            AsistenciaSeeder::class,
            IncidenciaConductaSeeder::class,
            BitacoraPsicologicaSeeder::class,
            PagoSeeder::class,
            NotificacionSeeder::class,
        ]);
    }
}
