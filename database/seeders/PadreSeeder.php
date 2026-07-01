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
            'Raúl', 'Gloria', 'Héctor', 'Verónica', 'César',
            'Liliana', 'Óscar', 'Mónica', 'Enrique', 'Beatriz',
            'Arturo', 'Sandra', 'Guillermo', 'Nancy', 'Alejandro',
            'Pilar', 'Sergio', 'Adriana', 'Rubén', 'Carolina',
            'Víctor', 'Julia', 'Andrés', 'Daniela', 'Germán',
            'Soledad', 'Raúl', 'Esther', 'Dante', 'Norma',
        ];

        $apellidos = [
            'Huamán', 'Quispe', 'Condori', 'Mamani', 'Chávez',
            'Rojas', 'Flores', 'Medina', 'Torres', 'Vargas',
            'Guzmán', 'Paredes', 'Salazar', 'Contreras', 'Delgado',
            'Campos', 'Vega', 'Castillo', 'Acosta', 'Suárez',
            'Espinoza', 'Ramos', 'Herrera', 'Núñez', 'Saavedra',
            'Zapata', 'Coronado', 'Luna', 'Miranda', 'Soto',
        ];

        $totalPadres = 300;
        $dniBase = 10000001;

        for ($i = 0; $i < $totalPadres; $i++) {
            $nombre = $nombres[$i % count($nombres)];
            $ap = $apellidos[$i % count($apellidos)];
            $am = $apellidos[($i + 11) % count($apellidos)];

            $user = User::factory()->create([
                'name' => "$nombre $ap $am",
                'email' => 'padre'.($i + 1).'@colegio.com',
                'role' => UserRole::Padre,
                'password' => Hash::make('password'),
            ]);

            Padre::create([
                'user_id' => $user->id,
                'nombres' => $nombre,
                'apellido_paterno' => $ap,
                'apellido_materno' => $am,
                'dni' => (string) ($dniBase + $i),
                'telefono' => fake()->numerify('9########'),
                'direccion' => fake()->address(),
            ]);
        }
    }
}
