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
            'Sofía', 'Mateo', 'Valeria', 'Sebastián', 'Renata',
            'Renzo', 'Luciana', 'Diego', 'Emilia', 'Nicolás',
            'Valentina', 'Joaquín', 'Mía', 'Matías', 'Antonella',
            'Sebastián', 'Camila', 'Alejandro', 'Isabella', 'Fernando',
            'Gabriela', 'Ricardo', 'Daniela', 'Andrés', 'Victoria',
            'Javier', 'Catalina', 'Miguel', 'Martina', 'Tomás',
            'Florencia', 'Benjamín', 'Renata', 'Lucas', 'Aitana',
            'Thiago', 'Mía', 'Santiago', 'Salomé', 'Samuel',
            'Bianca', 'Emiliano', 'Ariana', 'Jerónimo', 'Ignacia',
            'Agustín', 'Celeste', 'Felipe', 'Paula', 'Maximiliano',
        ];

        $apellidos = [
            'Huamán', 'Quispe', 'Condori', 'Mamani', 'Chávez',
            'Rojas', 'Flores', 'Medina', 'Torres', 'Vargas',
            'Guzmán', 'Paredes', 'Salazar', 'Contreras', 'Delgado',
            'Campos', 'Vega', 'Castillo', 'Acosta', 'Suárez',
        ];

        $grados = [1, 2, 3, 4, 5, 6];
        $secciones = ['A', 'B'];
        $alumnosPorSeccion = 25;
        $dniBase = 70000001;
        $alumnoIndex = 0;

        foreach ($grados as $grado) {
            $anioNacimiento = 2020 - $grado;
            $anioFin = $anioNacimiento + 1;

            foreach ($secciones as $seccion) {
                for ($i = 0; $i < $alumnosPorSeccion; $i++) {
                    $nombreIdx = $alumnoIndex % count($nombres);
                    $apellidoIdx = $alumnoIndex % count($apellidos);

                    $nombre = $nombres[$nombreIdx];
                    $ap = $apellidos[$apellidoIdx];
                    $am = $apellidos[($alumnoIndex + 7) % count($apellidos)];

                    $user = User::factory()->create([
                        'name' => "$nombre $ap $am",
                        'email' => 'alumno'.($alumnoIndex + 1).'@colegio.com',
                        'role' => UserRole::Alumno,
                        'password' => Hash::make('password'),
                    ]);

                    Alumno::create([
                        'user_id' => $user->id,
                        'nombres' => $nombre,
                        'apellido_paterno' => $ap,
                        'apellido_materno' => $am,
                        'fecha_nacimiento' => fake()->dateTimeBetween("$anioNacimiento-03-01", "$anioFin-02-28"),
                        'dni' => (string) ($dniBase + $alumnoIndex),
                        'grado' => $grado,
                        'seccion' => $seccion,
                    ]);

                    $alumnoIndex++;
                }
            }
        }
    }
}
