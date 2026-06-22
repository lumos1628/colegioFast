<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Alumno;
use App\Models\Docente;
use App\Models\Padre;
use App\Models\User;
use Illuminate\Database\Seeder;

class RoleProfileSeeder extends Seeder
{
    public function run(): void
    {
        $docenteUser = User::where('role', UserRole::Docente)->first();
        if ($docenteUser && ! Docente::where('user_id', $docenteUser->id)->exists()) {
            Docente::create([
                'user_id' => $docenteUser->id,
                'especialidad' => 'Matemática',
                'telefono' => '999999999',
            ]);
        }

        $alumnoUser = User::where('role', UserRole::Alumno)->first();
        if ($alumnoUser && ! Alumno::where('user_id', $alumnoUser->id)->exists()) {
            Alumno::create([
                'user_id' => $alumnoUser->id,
                'nombres' => 'Alumno',
                'apellido_paterno' => 'Demo',
                'apellido_materno' => 'Demo',
                'fecha_nacimiento' => '2015-01-01',
                'dni' => '12345678',
                'grado' => 1,
                'seccion' => 'A',
            ]);
        }

        $padreUser = User::where('role', UserRole::Padre)->first();
        if ($padreUser && ! Padre::where('user_id', $padreUser->id)->exists()) {
            Padre::create([
                'user_id' => $padreUser->id,
                'nombres' => 'Padre',
                'apellido_paterno' => 'Demo',
                'apellido_materno' => 'Demo',
                'dni' => '87654321',
                'telefono' => '988888888',
                'direccion' => 'Av. Demo 123',
            ]);
        }
    }
}
