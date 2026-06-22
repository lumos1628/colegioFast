<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Alumno;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AlumnoSeeder extends Seeder
{
    public function run(): void
    {
        $nombres = [
            'Sofía', 'Valentina', 'Renato', 'Mateo', 'Luciana',
            'Emilia', 'Sebastián', 'Isabella', 'Nicolás', 'Camila',
            'Diego', 'Mariana', 'Andrés', 'Gabriela', 'Fernando',
            'Paula', 'Ricardo', 'Daniela', 'Alejandro', 'Victoria',
            'Javier', 'Catalina', 'Miguel', 'Antonella', 'Rodrigo',
            'Martina', 'Tomás', 'Florencia', 'Matías', 'Agustina',
            'Joaquín', 'Bianca', 'Samuel', 'Emilia', 'Benjamín',
            'Renata', 'Lucas', 'Aitana', 'Thiago', 'Mía',
        ];

        $apellidos = [
            'García', 'Rodríguez', 'Martínez', 'López', 'González',
            'Hernández', 'Pérez', 'Sánchez', 'Ramírez', 'Torres',
            'Flores', 'Rivera', 'Gómez', 'Díaz', 'Reyes',
            'Morales', 'Vargas', 'Castro', 'Ortiz', 'Silva',
        ];

        for ($i = 0; $i < 40; $i++) {
            $user = User::factory()->create([
                'name' => $nombres[$i].' '.$apellidos[$i % count($apellidos)],
                'email' => 'alumno'.($i + 1).'@colegio.com',
                'role' => UserRole::Alumno,
                'password' => Hash::make('password'),
            ]);

            Alumno::create([
                'user_id' => $user->id,
                'nombres' => $nombres[$i],
                'apellido_paterno' => $apellidos[$i % count($apellidos)],
                'apellido_materno' => $apellidos[($i + 5) % count($apellidos)],
                'fecha_nacimiento' => fake()->dateTimeBetween('-12 years', '-6 years'),
                'dni' => str_pad((string) ($i + 100), 8, '0', STR_PAD_LEFT),
                'grado' => fake()->numberBetween(1, 3),
                'seccion' => 'A',
            ]);
        }
    }
}
