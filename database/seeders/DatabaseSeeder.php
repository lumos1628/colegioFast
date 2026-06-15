<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

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
    }
}
