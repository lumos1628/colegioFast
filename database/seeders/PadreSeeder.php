<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Padre;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PadreSeeder extends Seeder
{
    public function run(): void
    {
        $nombres = [
            'Roberto', 'Claudia', 'Fernando', 'María', 'Luis',
            'Patricia', 'Jorge', 'Silvia', 'Pedro', 'Ana',
            'Miguel', 'Carmen', 'José', 'Rosa', 'Antonio',
            'Teresa', 'Francisco', 'Isabel', 'Manuel', 'Elena',
        ];

        $apellidos = [
            'García', 'Rodríguez', 'Martínez', 'López', 'González',
            'Hernández', 'Pérez', 'Sánchez', 'Ramírez', 'Torres',
        ];

        for ($i = 0; $i < 20; $i++) {
            $user = User::factory()->create([
                'name' => $nombres[$i].' '.$apellidos[$i % count($apellidos)],
                'email' => 'padre'.($i + 1).'@colegio.com',
                'role' => UserRole::Padre,
                'password' => Hash::make('password'),
            ]);

            Padre::create([
                'user_id' => $user->id,
                'nombres' => $nombres[$i],
                'apellido_paterno' => $apellidos[$i % count($apellidos)],
                'apellido_materno' => $apellidos[($i + 3) % count($apellidos)],
                'dni' => str_pad((string) ($i + 200), 8, '0', STR_PAD_LEFT),
                'telefono' => fake()->numerify('9########'),
                'direccion' => fake()->address(),
            ]);
        }
    }
}
