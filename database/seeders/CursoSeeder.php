<?php

namespace Database\Seeders;

use App\Models\Curso;
use Illuminate\Database\Seeder;

class CursoSeeder extends Seeder
{
    public function run(): void
    {
        $cursos = [
            ['nombre' => 'Matemática', 'area_curricular' => 'Matemática', 'grado' => 1, 'seccion' => 'A'],
            ['nombre' => 'Matemática', 'area_curricular' => 'Matemática', 'grado' => 2, 'seccion' => 'A'],
            ['nombre' => 'Matemática', 'area_curricular' => 'Matemática', 'grado' => 3, 'seccion' => 'A'],
            ['nombre' => 'Comunicación', 'area_curricular' => 'Comunicación', 'grado' => 1, 'seccion' => 'A'],
            ['nombre' => 'Comunicación', 'area_curricular' => 'Comunicación', 'grado' => 2, 'seccion' => 'A'],
            ['nombre' => 'Comunicación', 'area_curricular' => 'Comunicación', 'grado' => 3, 'seccion' => 'A'],
            ['nombre' => 'Personal Social', 'area_curricular' => 'Personal Social', 'grado' => 1, 'seccion' => 'A'],
            ['nombre' => 'Personal Social', 'area_curricular' => 'Personal Social', 'grado' => 2, 'seccion' => 'A'],
            ['nombre' => 'Personal Social', 'area_curricular' => 'Personal Social', 'grado' => 3, 'seccion' => 'A'],
            ['nombre' => 'Ciencia y Tecnología', 'area_curricular' => 'Ciencia y Tecnología', 'grado' => 1, 'seccion' => 'A'],
            ['nombre' => 'Ciencia y Tecnología', 'area_curricular' => 'Ciencia y Tecnología', 'grado' => 2, 'seccion' => 'A'],
            ['nombre' => 'Ciencia y Tecnología', 'area_curricular' => 'Ciencia y Tecnología', 'grado' => 3, 'seccion' => 'A'],
            ['nombre' => 'Arte y Cultura', 'area_curricular' => 'Arte y Cultura', 'grado' => 1, 'seccion' => 'A'],
            ['nombre' => 'Educación Física', 'area_curricular' => 'Educación Física', 'grado' => 1, 'seccion' => 'A'],
            ['nombre' => 'Inglés', 'area_curricular' => 'Inglés', 'grado' => 1, 'seccion' => 'A'],
        ];

        foreach ($cursos as $curso) {
            Curso::create($curso);
        }
    }
}
