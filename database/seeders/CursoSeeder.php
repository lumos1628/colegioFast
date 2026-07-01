<?php

namespace Database\Seeders;

use App\Models\Curso;
use Illuminate\Database\Seeder;

class CursoSeeder extends Seeder
{
    public function run(): void
    {
        $areas = [
            ['nombre' => 'Comunicación', 'area_curricular' => 'Comunicación'],
            ['nombre' => 'Matemática', 'area_curricular' => 'Matemática'],
            ['nombre' => 'Personal Social', 'area_curricular' => 'Personal Social'],
            ['nombre' => 'Ciencia y Tecnología', 'area_curricular' => 'Ciencia y Tecnología'],
            ['nombre' => 'Arte y Cultura', 'area_curricular' => 'Arte y Cultura'],
            ['nombre' => 'Educación Física', 'area_curricular' => 'Educación Física'],
            ['nombre' => 'Educación Religiosa', 'area_curricular' => 'Educación Religiosa'],
        ];

        $grados = [1, 2, 3, 4, 5, 6];
        $secciones = ['A', 'B'];

        foreach ($grados as $grado) {
            foreach ($secciones as $seccion) {
                foreach ($areas as $area) {
                    Curso::create([
                        'nombre' => $area['nombre'],
                        'area_curricular' => $area['area_curricular'],
                        'grado' => $grado,
                        'seccion' => $seccion,
                    ]);
                }
            }
        }
    }
}
