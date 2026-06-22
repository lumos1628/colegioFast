<?php

namespace Database\Seeders;

use App\Models\Competencia;
use Illuminate\Database\Seeder;

class CompetenciaSeeder extends Seeder
{
    public function run(): void
    {
        $competencias = [
            ['nombre' => 'Resuelve problemas de cantidad', 'area_curricular' => 'Matemática'],
            ['nombre' => 'Resuelve problemas de regularidad, equivalencia y cambio', 'area_curricular' => 'Matemática'],
            ['nombre' => 'Resuelve problemas de forma, movimiento y localización', 'area_curricular' => 'Matemática'],
            ['nombre' => 'Se comunica oralmente en su lengua materna', 'area_curricular' => 'Comunicación'],
            ['nombre' => 'Lee diversos tipos de textos escritos en su lengua materna', 'area_curricular' => 'Comunicación'],
            ['nombre' => 'Escribe diversos tipos de textos en su lengua materna', 'area_curricular' => 'Comunicación'],
            ['nombre' => 'Convive y participa democráticamente en la búsqueda del bien común', 'area_curricular' => 'Personal Social'],
            ['nombre' => 'Construye su identidad', 'area_curricular' => 'Personal Social'],
        ];

        foreach ($competencias as $competencia) {
            Competencia::create($competencia);
        }
    }
}
