<?php

namespace Database\Seeders;

use App\Models\Actividad;
use App\Models\Asignacion;
use App\Models\Competencia;
use Illuminate\Database\Seeder;

class ActividadSeeder extends Seeder
{
    public function run(): void
    {
        $competencias = Competencia::with('capacidades')->get();

        $competenciasPorArea = [];
        foreach ($competencias as $competencia) {
            $competenciasPorArea[$competencia->area_curricular][] = $competencia;
        }

        $plantillas = $this->getPlantillas();

        $asignaciones = Asignacion::with(['curso', 'periodoAcademico'])->get();

        foreach ($asignaciones as $asignacion) {
            $area = $asignacion->curso->area_curricular;
            $grado = $asignacion->curso->grado;
            $periodo = $asignacion->periodoAcademico;

            $plantillasArea = $plantillas[$area] ?? [];
            if (empty($plantillasArea)) {
                continue;
            }

            $cantidadActividades = rand(3, 4);
            $plantillasSeleccionadas = collect($plantillasArea)->random(min($cantidadActividades, count($plantillasArea)));

            if ($plantillasSeleccionadas->count() < $cantidadActividades) {
                $faltantes = $cantidadActividades - $plantillasSeleccionadas->count();
                $adicionales = collect($plantillasArea)->except($plantillasSeleccionadas->pluck('titulo')->all())->random(min($faltantes, count($plantillasArea) - $plantillasSeleccionadas->count()));
                $plantillasSeleccionadas = $plantillasSeleccionadas->merge($adicionales);
            }

            foreach ($plantillasSeleccionadas as $plantilla) {
                $competencia = collect($competenciasPorArea[$area] ?? [])->firstWhere('nombre', $plantilla['competencia']);
                if (! $competencia) {
                    continue;
                }

                $capacidad = $competencia->capacidades->firstWhere('nombre', $plantilla['capacidad']);
                if (! $capacidad) {
                    continue;
                }

                $fecha = $this->fechaDentroDePeriodo($periodo->fecha_inicio, $periodo->fecha_fin, $asignacion->dia_semana);

                Actividad::create([
                    'asignacion_id' => $asignacion->id,
                    'titulo' => $this->adaptarTitulo($plantilla['titulo'], $grado),
                    'descripcion' => $plantilla['descripcion'] ?? fake()->paragraph(),
                    'fecha' => $fecha,
                    'competencia_id' => $competencia->id,
                    'capacidad_id' => $capacidad->id,
                ]);
            }
        }
    }

    private function fechaDentroDePeriodo($fechaInicio, $fechaFin, int $diaSemana): string
    {
        $fecha = $fechaInicio->copy();
        $fechas = [];

        while ($fecha->lessThanOrEqualTo($fechaFin)) {
            if ($fecha->dayOfWeekIso === $diaSemana) {
                $fechas[] = $fecha->copy();
            }
            $fecha->addDay();
        }

        if (empty($fechas)) {
            return $fechaInicio->format('Y-m-d');
        }

        return $fechas[array_rand($fechas)]->format('Y-m-d');
    }

    private function adaptarTitulo(string $titulo, int $grado): string
    {
        $adaptaciones = [
            1 => ['números hasta el 20' => 'números hasta el 20', 'cuento corto' => 'cuento corto', 'texto breve' => 'texto breve'],
            2 => ['números hasta el 50' => 'números hasta el 50', 'cuento' => 'cuento', 'texto narrativo' => 'texto narrativo'],
            3 => ['números hasta el 100' => 'números hasta el 100', 'texto informativo' => 'texto informativo'],
            4 => ['fracciones' => 'fracciones', 'números hasta el 1000' => 'números hasta el 1000'],
            5 => ['decimales' => 'decimales', 'ecuaciones simples' => 'ecuaciones simples'],
            6 => ['porcentajes' => 'porcentajes', 'ecuaciones' => 'ecuaciones'],
        ];

        return $titulo;
    }

    private function getPlantillas(): array
    {
        return [
            'Comunicación' => [
                [
                    'titulo' => 'Narro historias de mi comunidad',
                    'competencia' => 'Se comunica oralmente en su lengua materna',
                    'capacidad' => 'Adecúa, organiza y desarrolla las ideas de forma coherente y cohesionada',
                    'descripcion' => 'Los estudiantes narran de forma oral historias vinculadas a su comunidad utilizando secuencia lógica y vocabulario adecuado.',
                ],
                [
                    'titulo' => 'Escucho y respondo a cuentos leídos en clase',
                    'competencia' => 'Se comunica oralmente en su lengua materna',
                    'capacidad' => 'Obtiene información del texto oral',
                    'descripcion' => 'Los estudiantes escuchan atentamente un cuento leído por el docente e identifican personajes, lugares y hechos principales.',
                ],
                [
                    'titulo' => 'Debatimos sobre el cuidado del medio ambiente',
                    'competencia' => 'Se comunica oralmente en su lengua materna',
                    'capacidad' => 'Infiere e interpreta información del texto oral',
                    'descripcion' => 'Los estudiantes participan en un debate expresando sus opiniones sobre el cuidado del medio ambiente con argumentos sencillos.',
                ],
                [
                    'titulo' => 'Leo cuentos y respondo preguntas',
                    'competencia' => 'Lee diversos tipos de textos escritos en su lengua materna',
                    'capacidad' => 'Obtiene información del texto escrito',
                    'descripcion' => 'Los estudiantes leen un cuento e identifican información explícita como personajes, lugar y hechos principales.',
                ],
                [
                    'titulo' => 'Leo y comprendo textos informativos sobre animales',
                    'competencia' => 'Lee diversos tipos de textos escritos en su lengua materna',
                    'capacidad' => 'Infiere e interpreta información del texto',
                    'descripcion' => 'Los estudiantes leen textos informativos sobre animales y deducen información implícita a partir de las imágenes y el texto.',
                ],
                [
                    'titulo' => 'Escribo una carta a mi familia',
                    'competencia' => 'Escribe diversos tipos de textos en su lengua materna',
                    'capacidad' => 'Adecúa el texto a la situación comunicativa',
                    'descripcion' => 'Los estudiantes escriben una carta dirigida a un familiar considerando el propósito, destinatario y estructura del texto.',
                ],
                [
                    'titulo' => 'Escribo la noticia de mi grado',
                    'competencia' => 'Escribe diversos tipos de textos en su lengua materna',
                    'capacidad' => 'Organiza y desarrolla las ideas de forma coherente y cohesionada',
                    'descripcion' => 'Los estudiantes redactan una noticia sobre un evento del aula organizando las ideas de forma secuencial y coherente.',
                ],
            ],
            'Matemática' => [
                [
                    'titulo' => 'Resuelvo problemas de suma y resta',
                    'competencia' => 'Resuelve problemas de cantidad',
                    'capacidad' => 'Traduce cantidades a expresiones numéricas',
                    'descripcion' => 'Los estudiantes resuelven problemas aditivos simples utilizando material concreto y expresiones numéricas.',
                ],
                [
                    'titulo' => 'Leo y escribo números en el tablero',
                    'competencia' => 'Resuelve problemas de cantidad',
                    'capacidad' => 'Comunica su comprensión sobre los números y las operaciones',
                    'descripcion' => 'Los estudiantes leen, escriben y ordenan números naturales utilizando el tablero posicional.',
                ],
                [
                    'titulo' => 'Estimulo y calculo cantidades del aula',
                    'competencia' => 'Resuelve problemas de cantidad',
                    'capacidad' => 'Usa estrategias y procedimientos de estimación y cálculo',
                    'descripcion' => 'Los estudiantes estiman cantidades de objetos del aula y verifican sus estimaciones mediante estrategias de cálculo.',
                ],
                [
                    'titulo' => 'Descubro patrones numéricos',
                    'competencia' => 'Resuelve problemas de regularidad, equivalencia y cambio',
                    'capacidad' => 'Traduce datos y condiciones a expresiones algebraicas',
                    'descripcion' => 'Los estudiantes identifican y continúan patrones de repetición y patrones aditivos simples.',
                ],
                [
                    'titulo' => 'Resuelvo problemas de equivalencia',
                    'competencia' => 'Resuelve problemas de regularidad, equivalencia y cambio',
                    'capacidad' => 'Usa estrategias y procedimientos para encontrar equivalencias y reglas generales',
                    'descripcion' => 'Los estudiantes encuentran equivalencias entre expresiones numéricas utilizando material concreto.',
                ],
                [
                    'titulo' => 'Reconozco formas geométricas en mi entorno',
                    'competencia' => 'Resuelve problemas de forma, movimiento y localización',
                    'capacidad' => 'Modela objetos con formas geométricas y sus transformaciones',
                    'descripcion' => 'Los estudiantes identifican formas geométricas básicas en objetos de su entorno y las representan gráficamente.',
                ],
                [
                    'titulo' => 'Me ubico en el plano y mapa del aula',
                    'competencia' => 'Resuelve problemas de forma, movimiento y localización',
                    'capacidad' => 'Usa estrategias y procedimientos para orientarse en el espacio',
                    'descripcion' => 'Los estudiantes se ubican en espacios conocidos y describen recorridos utilizando nociones de dirección y distancia.',
                ],
            ],
            'Personal Social' => [
                [
                    'titulo' => 'Me conozco y reconozco mis cualidades',
                    'competencia' => 'Construye su identidad',
                    'capacidad' => 'Se valora a sí mismo',
                    'descripcion' => 'Los estudiantes identifican sus características personales, cualidades y aspectos que los hacen únicos.',
                ],
                [
                    'titulo' => 'Reconozco y expreso mis emociones',
                    'competencia' => 'Construye su identidad',
                    'capacidad' => 'Autorregula sus emociones',
                    'descripcion' => 'Los estudiantes identifican emociones en sí mismos y en otros, y practican estrategias sencillas de autorregulación.',
                ],
                [
                    'titulo' => 'Qué es lo justo y lo injusto en el aula',
                    'competencia' => 'Construye su identidad',
                    'capacidad' => 'Reflexiona y argumenta éticamente',
                    'descripcion' => 'Los estudiantes reflexionan sobre situaciones de justicia e injusticia en el aula y argumentan sus posiciones.',
                ],
                [
                    'titulo' => 'Convivimos mejor cuando nos respetamos',
                    'competencia' => 'Convive y participa democráticamente en la búsqueda del bien común',
                    'capacidad' => 'Interactúa con todas las personas',
                    'descripcion' => 'Los estudiantes reconocen la importancia del respeto y la inclusión en la convivencia del aula.',
                ],
                [
                    'titulo' => 'Elaboramos las normas de convivencia del aula',
                    'competencia' => 'Convive y participa democráticamente en la búsqueda del bien común',
                    'capacidad' => 'Construye normas y asume acuerdos y leyes',
                    'descripcion' => 'Los estudiantes participan en la elaboración de normas de convivencia del aula de manera democrática.',
                ],
                [
                    'titulo' => 'Resuelvo conflictos dialogando',
                    'competencia' => 'Convive y participa democráticamente en la búsqueda del bien común',
                    'capacidad' => 'Maneja conflictos de manera constructiva',
                    'descripcion' => 'Los estudiantes practican el diálogo como estrategia para resolver conflictos cotidianos en el aula.',
                ],
            ],
            'Ciencia y Tecnología' => [
                [
                    'titulo' => 'Indago sobre los seres vivos de mi entorno',
                    'competencia' => 'Indaga mediante métodos científicos para construir conocimientos',
                    'capacidad' => 'Problematiza situaciones para hacer indagación',
                    'descripcion' => 'Los estudiantes formulan preguntas sobre los seres vivos de su entorno y plantean posibles respuestas.',
                ],
                [
                    'titulo' => 'Diseño un experimento con plantas',
                    'competencia' => 'Indaga mediante métodos científicos para construir conocimientos',
                    'capacidad' => 'Diseña estrategias para hacer indagación',
                    'descripcion' => 'Los estudiantes diseñan un plan de indagación para observar el crecimiento de las plantas.',
                ],
                [
                    'titulo' => 'Observo y registro datos del clima',
                    'competencia' => 'Indaga mediante métodos científicos para construir conocimientos',
                    'capacidad' => 'Genera y registra datos e información',
                    'descripcion' => 'Los estudiantes observan y registran datos del clima durante una semana utilizando instrumentos sencillos.',
                ],
                [
                    'titulo' => 'Analizo los resultados de mi experimento',
                    'competencia' => 'Indaga mediante métodos científicos para construir conocimientos',
                    'capacidad' => 'Analiza datos e información y obtiene conclusiones',
                    'descripcion' => 'Los estudiantes analizan los datos recogidos en su experimento y elaboran conclusiones sencillas.',
                ],
                [
                    'titulo' => 'Diseño un juguete con material reciclado',
                    'competencia' => 'Diseña y construye soluciones tecnológicas para resolver problemas de su entorno',
                    'capacidad' => 'Determina una alternativa de solución tecnológica',
                    'descripcion' => 'Los estudiantes identifican un problema de su entorno y proponen una solución tecnológica usando material reciclado.',
                ],
                [
                    'titulo' => 'Construyo un instrumento musical',
                    'competencia' => 'Diseña y construye soluciones tecnológicas para resolver problemas de su entorno',
                    'capacidad' => 'Implementa y valida la alternativa de solución tecnológica',
                    'descripcion' => 'Los estudiantes construyen un instrumento musical con material reciclado y verifican su funcionamiento.',
                ],
            ],
            'Arte y Cultura' => [
                [
                    'titulo' => 'Aprecio los colores y formas en obras de artistas peruanos',
                    'competencia' => 'Aprecia de manera crítica manifestaciones artístico-culturales',
                    'capacidad' => 'Percibe manifestaciones artístico-culturales para apreciarlas',
                    'descripcion' => 'Los estudiantes observan obras de artistas peruanos y describen los elementos visuales que perciben.',
                ],
                [
                    'titulo' => 'Conozco danzas típicas de mi región',
                    'competencia' => 'Aprecia de manera crítica manifestaciones artístico-culturales',
                    'capacidad' => 'Contextualiza manifestaciones artístico-culturales',
                    'descripcion' => 'Los estudiantes investigan y comentan sobre danzas típicas de su región y su importancia cultural.',
                ],
                [
                    'titulo' => 'Exploro colores y texturas con técnicas mixtas',
                    'competencia' => 'Crea proyectos desde los lenguajes artísticos',
                    'capacidad' => 'Explora y experimenta los lenguajes del arte',
                    'descripcion' => 'Los estudiantes exploran diferentes técnicas artísticas como acuarela, collage y modelado.',
                ],
                [
                    'titulo' => 'Creo mi propia obra de arte',
                    'competencia' => 'Crea proyectos desde los lenguajes artísticos',
                    'capacidad' => 'Aplica procesos creativos',
                    'descripcion' => 'Los estudiantes planifican y crean una obra artística original utilizando los elementos del arte.',
                ],
                [
                    'titulo' => 'Presento mi proyecto artístico al aula',
                    'competencia' => 'Crea proyectos desde los lenguajes artísticos',
                    'capacidad' => 'Socializa sus procesos y proyectos',
                    'descripcion' => 'Los estudiantes presentan y explican sus proyectos artísticos al grupo, describiendo su proceso creativo.',
                ],
            ],
            'Educación Física' => [
                [
                    'titulo' => 'Conozco mi cuerpo y sus movimientos',
                    'competencia' => 'Se desenvuelve de manera autónoma a través de su motricidad',
                    'capacidad' => 'Comprende su cuerpo y reconoce sus posibilidades de movimiento',
                    'descripcion' => 'Los estudiantes identifican las partes de su cuerpo y exploran sus posibilidades de movimiento.',
                ],
                [
                    'titulo' => 'Practico juegos motores y circuitos',
                    'competencia' => 'Se desenvuelve de manera autónoma a través de su motricidad',
                    'capacidad' => 'Se expresa corporalmente y aplica sus habilidades motrices',
                    'descripcion' => 'Los estudiantes participan en juegos motores y circuitos que combinan desplazamientos, saltos y lanzamientos.',
                ],
                [
                    'titulo' => 'Aprendo sobre alimentación saludable',
                    'competencia' => 'Asume una vida saludable',
                    'capacidad' => 'Comprende las relaciones entre actividad física, alimentación, postura e higiene y la salud',
                    'descripcion' => 'Los estudiantes reconocen la importancia de una alimentación saludable y su relación con la actividad física.',
                ],
                [
                    'titulo' => 'Practico hábitos de higiene y postura',
                    'competencia' => 'Asume una vida saludable',
                    'capacidad' => 'Incorpora prácticas de vida saludable',
                    'descripcion' => 'Los estudiantes practican hábitos de higiene personal y posturas correctas durante la actividad física.',
                ],
            ],
            'Educación Religiosa' => [
                [
                    'titulo' => 'Dios me creó con amor',
                    'competencia' => 'Construye su identidad como persona humana, amada por Dios',
                    'capacidad' => 'Conoce a Dios y a su plan de amor',
                    'descripcion' => 'Los estudiantes reflexionan sobre la creación como obra de amor de Dios y reconocen su dignidad como personas.',
                ],
                [
                    'titulo' => 'Reconozco a Dios en la naturaleza',
                    'competencia' => 'Construye su identidad como persona humana, amada por Dios',
                    'capacidad' => 'Reconoce la presencia de Dios en su vida y en el mundo',
                    'descripcion' => 'Los estudiantes observan la naturaleza y reconocen en ella la presencia y el amor de Dios.',
                ],
                [
                    'titulo' => 'Historias de amor y esperanza',
                    'competencia' => 'Asume la experiencia del encuentro personal y comunitario con Dios',
                    'capacidad' => 'Asume el mensaje de Jesucristo y su propuesta de salvación',
                    'descripcion' => 'Los estudiantes conocen historias bíblicas que transmiten mensajes de amor, esperanza y solidaridad.',
                ],
                [
                    'titulo' => 'Vivo mis valores en familia y comunidad',
                    'competencia' => 'Asume la experiencia del encuentro personal y comunitario con Dios',
                    'capacidad' => 'Vive su fe de manera coherente con su conducta',
                    'descripcion' => 'Los estudiantes identifican acciones concretas para vivir los valores cristianos en su familia y comunidad.',
                ],
            ],
        ];
    }
}
