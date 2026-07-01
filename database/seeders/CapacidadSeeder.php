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
            [
                'competencia' => 'Se comunica oralmente en su lengua materna',
                'capacidades' => [
                    'Obtiene información del texto oral',
                    'Infiere e interpreta información del texto oral',
                    'Adecúa, organiza y desarrolla las ideas de forma coherente y cohesionada',
                ],
            ],
            [
                'competencia' => 'Lee diversos tipos de textos escritos en su lengua materna',
                'capacidades' => [
                    'Obtiene información del texto escrito',
                    'Infiere e interpreta información del texto',
                    'Reflexiona y evalúa la forma, el contenido y contexto del texto',
                ],
            ],
            [
                'competencia' => 'Escribe diversos tipos de textos en su lengua materna',
                'capacidades' => [
                    'Adecúa el texto a la situación comunicativa',
                    'Organiza y desarrolla las ideas de forma coherente y cohesionada',
                    'Utiliza convenciones del lenguaje escrito de forma pertinente',
                ],
            ],
            [
                'competencia' => 'Resuelve problemas de cantidad',
                'capacidades' => [
                    'Traduce cantidades a expresiones numéricas',
                    'Comunica su comprensión sobre los números y las operaciones',
                    'Usa estrategias y procedimientos de estimación y cálculo',
                ],
            ],
            [
                'competencia' => 'Resuelve problemas de regularidad, equivalencia y cambio',
                'capacidades' => [
                    'Traduce datos y condiciones a expresiones algebraicas',
                    'Usa estrategias y procedimientos para encontrar equivalencias y reglas generales',
                ],
            ],
            [
                'competencia' => 'Resuelve problemas de forma, movimiento y localización',
                'capacidades' => [
                    'Modela objetos con formas geométricas y sus transformaciones',
                    'Comunica su comprensión sobre las formas y relaciones geométricas',
                    'Usa estrategias y procedimientos para orientarse en el espacio',
                ],
            ],
            [
                'competencia' => 'Construye su identidad',
                'capacidades' => [
                    'Se valora a sí mismo',
                    'Autorregula sus emociones',
                    'Reflexiona y argumenta éticamente',
                ],
            ],
            [
                'competencia' => 'Convive y participa democráticamente en la búsqueda del bien común',
                'capacidades' => [
                    'Interactúa con todas las personas',
                    'Construye normas y asume acuerdos y leyes',
                    'Maneja conflictos de manera constructiva',
                ],
            ],
            [
                'competencia' => 'Indaga mediante métodos científicos para construir conocimientos',
                'capacidades' => [
                    'Problematiza situaciones para hacer indagación',
                    'Diseña estrategias para hacer indagación',
                    'Genera y registra datos e información',
                    'Analiza datos e información y obtiene conclusiones',
                ],
            ],
            [
                'competencia' => 'Diseña y construye soluciones tecnológicas para resolver problemas de su entorno',
                'capacidades' => [
                    'Determina una alternativa de solución tecnológica',
                    'Diseña la alternativa de solución tecnológica',
                    'Implementa y valida la alternativa de solución tecnológica',
                ],
            ],
            [
                'competencia' => 'Aprecia de manera crítica manifestaciones artístico-culturales',
                'capacidades' => [
                    'Percibe manifestaciones artístico-culturales para apreciarlas',
                    'Contextualiza manifestaciones artístico-culturales',
                ],
            ],
            [
                'competencia' => 'Crea proyectos desde los lenguajes artísticos',
                'capacidades' => [
                    'Explora y experimenta los lenguajes del arte',
                    'Aplica procesos creativos',
                    'Socializa sus procesos y proyectos',
                ],
            ],
            [
                'competencia' => 'Se desenvuelve de manera autónoma a través de su motricidad',
                'capacidades' => [
                    'Comprende su cuerpo y reconoce sus posibilidades de movimiento',
                    'Se expresa corporalmente y aplica sus habilidades motrices',
                ],
            ],
            [
                'competencia' => 'Asume una vida saludable',
                'capacidades' => [
                    'Comprende las relaciones entre actividad física, alimentación, postura e higiene y la salud',
                    'Incorpora prácticas de vida saludable',
                ],
            ],
            [
                'competencia' => 'Construye su identidad como persona humana, amada por Dios',
                'capacidades' => [
                    'Conoce a Dios y a su plan de amor',
                    'Reconoce la presencia de Dios en su vida y en el mundo',
                ],
            ],
            [
                'competencia' => 'Asume la experiencia del encuentro personal y comunitario con Dios',
                'capacidades' => [
                    'Asume el mensaje de Jesucristo y su propuesta de salvación',
                    'Vive su fe de manera coherente con su conducta',
                ],
            ],
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
