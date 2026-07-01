<?php

namespace Database\Seeders;

use App\Models\Competencia;
use Illuminate\Database\Seeder;

class CompetenciaSeeder extends Seeder
{
    public function run(): void
    {
        $competencias = [
            ['nombre' => 'Se comunica oralmente en su lengua materna', 'area_curricular' => 'Comunicación'],
            ['nombre' => 'Lee diversos tipos de textos escritos en su lengua materna', 'area_curricular' => 'Comunicación'],
            ['nombre' => 'Escribe diversos tipos de textos en su lengua materna', 'area_curricular' => 'Comunicación'],

            ['nombre' => 'Resuelve problemas de cantidad', 'area_curricular' => 'Matemática'],
            ['nombre' => 'Resuelve problemas de regularidad, equivalencia y cambio', 'area_curricular' => 'Matemática'],
            ['nombre' => 'Resuelve problemas de forma, movimiento y localización', 'area_curricular' => 'Matemática'],

            ['nombre' => 'Construye su identidad', 'area_curricular' => 'Personal Social'],
            ['nombre' => 'Convive y participa democráticamente en la búsqueda del bien común', 'area_curricular' => 'Personal Social'],

            ['nombre' => 'Indaga mediante métodos científicos para construir conocimientos', 'area_curricular' => 'Ciencia y Tecnología'],
            ['nombre' => 'Diseña y construye soluciones tecnológicas para resolver problemas de su entorno', 'area_curricular' => 'Ciencia y Tecnología'],

            ['nombre' => 'Aprecia de manera crítica manifestaciones artístico-culturales', 'area_curricular' => 'Arte y Cultura'],
            ['nombre' => 'Crea proyectos desde los lenguajes artísticos', 'area_curricular' => 'Arte y Cultura'],

            ['nombre' => 'Se desenvuelve de manera autónoma a través de su motricidad', 'area_curricular' => 'Educación Física'],
            ['nombre' => 'Asume una vida saludable', 'area_curricular' => 'Educación Física'],

            ['nombre' => 'Construye su identidad como persona humana, amada por Dios', 'area_curricular' => 'Educación Religiosa'],
            ['nombre' => 'Asume la experiencia del encuentro personal y comunitario con Dios', 'area_curricular' => 'Educación Religiosa'],
        ];

        foreach ($competencias as $competencia) {
            Competencia::create($competencia);
        }
    }
}
