<?php

namespace Database\Seeders;

use App\Models\Capacidad;
use App\Models\Competencia;
use Illuminate\Database\Seeder;

class CapacidadSeeder extends Seeder
{
    public function run(): void
    {
        $capacidades = [
            ['competencia' => 'Resuelve problemas de cantidad', 'capacidades' => [
                'Traduce cantidades a expresiones numéricas',
                'Comunica su comprensión sobre los números y las operaciones',
                'Usa estrategias y procedimientos de estimación y cálculo',
            ]],
            ['competencia' => 'Resuelve problemas de regularidad, equivalencia y cambio', 'capacidades' => [
                'Traduce datos y condiciones a expresiones algebraicas',
                'Comunica su comprensión sobre las relaciones algebraicas',
                'Usa estrategias y procedimientos para encontrar equivalencias y reglas generales',
            ]],
            ['competencia' => 'Resuelve problemas de forma, movimiento y localización', 'capacidades' => [
                'Modela objetos con formas geométricas y sus transformaciones',
                'Comunica su comprensión sobre las formas y relaciones geométricas',
                'Usa estrategias y procedimientos para orientarse en el espacio',
            ]],
            ['competencia' => 'Se comunica oralmente en su lengua materna', 'capacidades' => [
                'Obtiene información del texto oral',
                'Infiere e interpreta información del texto oral',
                'Adecúa, organiza y desarrolla las ideas de forma coherente y cohesionada',
            ]],
            ['competencia' => 'Lee diversos tipos de textos escritos en su lengua materna', 'capacidades' => [
                'Obtiene información del texto escrito',
                'Infiere e interpreta información del texto',
                'Reflexiona y evalúa la forma, el contenido y contexto del texto',
            ]],
            ['competencia' => 'Escribe diversos tipos de textos en su lengua materna', 'capacidades' => [
                'Adecúa el texto a la situación comunicativa',
                'Organiza y desarrolla las ideas de forma coherente y cohesionada',
                'Utiliza convenciones del lenguaje escrito de forma pertinente',
            ]],
            ['competencia' => 'Convive y participa democráticamente en la búsqueda del bien común', 'capacidades' => [
                'Interactúa con todas las personas',
                'Construye normas y asume acuerdos y leyes',
                'Maneja conflictos de manera constructiva',
            ]],
            ['competencia' => 'Construye su identidad', 'capacidades' => [
                'Se valora a sí mismo',
                'Autorregula sus emociones',
                'Reflexiona y argumenta éticamente',
            ]],
        ];

        foreach ($capacidades as $grupo) {
            $competencia = Competencia::where('nombre', $grupo['competencia'])->first();
            if ($competencia) {
                foreach ($grupo['capacidades'] as $capacidad) {
                    Capacidad::create([
                        'competencia_id' => $competencia->id,
                        'nombre' => $capacidad,
                    ]);
                }
            }
        }
    }
}
