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
        $areas = [
            'Comunicación' => [
                ['name' => 'Carmen Rosa Flores Chávez', 'email' => 'carmen.flores@colegio.com'],
                ['name' => 'Jorge Luis Medina Salazar', 'email' => 'jorge.medina@colegio.com'],
                ['name' => 'Lucía Fernanda Ríos Cárdenas', 'email' => 'lucia.rios@colegio.com'],
            ],
            'Matemática' => [
                ['name' => 'Roberto Carlos Huamán Quispe', 'email' => 'roberto.huaman@colegio.com'],
                ['name' => 'Patricia Elena Mendoza Vera', 'email' => 'patricia.mendoza@colegio.com'],
                ['name' => 'Miguel Ángel Paredes Soto', 'email' => 'miguel.paredes@colegio.com'],
            ],
            'Personal Social' => [
                ['name' => 'María del Pilar Espinoza Ramos', 'email' => 'maria.espinoza@colegio.com'],
                ['name' => 'Luis Alberto Contreras Díaz', 'email' => 'luis.contreras@colegio.com'],
                ['name' => 'Ana María Velásquez Guerrero', 'email' => 'ana.velasquez@colegio.com'],
            ],
            'Ciencia y Tecnología' => [
                ['name' => 'Carlos Enrique Rojas Vargas', 'email' => 'carlos.rojas@colegio.com'],
                ['name' => 'Sofía Alejandra Guzmán Ponce', 'email' => 'sofia.guzman@colegio.com'],
                ['name' => 'Fernando José Delgado Miranda', 'email' => 'fernando.delgado@colegio.com'],
            ],
            'Arte y Cultura' => [
                ['name' => 'Claudia Beatriz Saavedra Luna', 'email' => 'claudia.saavedra@colegio.com'],
                ['name' => 'Ricardo Antonio Campos Herrera', 'email' => 'ricardo.campos@colegio.com'],
                ['name' => 'Isabel Cristina Núñez Cabrera', 'email' => 'isabel.nunez@colegio.com'],
            ],
            'Educación Física' => [
                ['name' => 'Diego Alejandro Torres Salinas', 'email' => 'diego.torres@colegio.com'],
                ['name' => 'Andrea Carolina Vega Maldonado', 'email' => 'andrea.vega@colegio.com'],
                ['name' => 'Oscar Daniel Castillo Reyes', 'email' => 'oscar.castillo@colegio.com'],
            ],
            'Educación Religiosa' => [
                ['name' => 'Rosa María Acosta Figueroa', 'email' => 'rosa.acosta@colegio.com'],
                ['name' => 'Pedro José Espinoza Coronado', 'email' => 'pedro.espinoza.r@colegio.com'],
                ['name' => 'Teresa del Carmen Zapata Suárez', 'email' => 'teresa.zapata@colegio.com'],
            ],
        ];

        $gradoPairs = [[1, 2], [3, 4], [5, 6]];

        foreach ($areas as $area => $docentes) {
            foreach ($docentes as $index => $data) {
                $user = User::factory()->create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'role' => UserRole::Docente,
                    'password' => Hash::make('password'),
                ]);

                $gradoLabel = $gradoPairs[$index][0].'°-'.$gradoPairs[$index][1].'°';

                Docente::create([
                    'user_id' => $user->id,
                    'especialidad' => $area,
                    'telefono' => $gradoLabel,
                ]);
            }
        }
    }
}
