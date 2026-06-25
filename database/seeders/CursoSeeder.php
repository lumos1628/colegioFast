<?php

namespace Database\Seeders;

use App\Models\Curso;
use Illuminate\Database\Seeder;

class CursoSeeder extends Seeder
{
    public function run(): void
    {
        $materias = [
            ['nombre' => 'Matemática', 'area_curricular' => 'Matemática'],
            ['nombre' => 'Comunicación', 'area_curricular' => 'Comunicación'],
            ['nombre' => 'Personal Social', 'area_curricular' => 'Personal Social'],
            ['nombre' => 'Ciencia y Tecnología', 'area_curricular' => 'Ciencia y Tecnología'],
            ['nombre' => 'Arte y Cultura', 'area_curricular' => 'Arte y Cultura'],
            ['nombre' => 'Educación Física', 'area_curricular' => 'Educación Física'],
            ['nombre' => 'Inglés', 'area_curricular' => 'Idiomas'],
            ['nombre' => 'Religión', 'area_curricular' => 'Educación Religiosa'],
        ];

        $grados = [1, 2, 3, 4, 5, 6];
        $secciones = ['A', 'B'];

        foreach ($grados as $grado) {
            foreach ($secciones as $seccion) {
                foreach ($materias as $materia) {
                    Curso::create([
                        'nombre' => $materia['nombre'],
                        'area_curricular' => $materia['area_curricular'],
                        'grado' => $grado,
                        'seccion' => $seccion,
                    ]);
                }
            }
        }
    }
}
