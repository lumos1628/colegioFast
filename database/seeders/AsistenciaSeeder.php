<?php

namespace Database\Seeders;

use App\Enums\AsistenciaEstado;
use App\Models\Asignacion;
use App\Models\Asistencia;
use App\Models\Matricula;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class AsistenciaSeeder extends Seeder
{
    public function run(): void
    {
        $hoy = Carbon::today();
        $diasHabiles = $this->ultimosDiasHabiles($hoy, 30);

        $asignaciones = Asignacion::with('periodoAcademico')->get();

        foreach ($asignaciones as $asignacion) {
            $matriculas = Matricula::where('asignacion_id', $asignacion->id)->get();

            if ($matriculas->isEmpty()) {
                continue;
            }

            $diaSemana = $asignacion->dia_semana;

            $fechasClase = collect($diasHabiles)->filter(function ($fecha) use ($diaSemana, $asignacion) {
                if ($fecha->dayOfWeekIso !== $diaSemana) {
                    return false;
                }
                $periodo = $asignacion->periodoAcademico;
                if ($periodo && $fecha->lessThan($periodo->fecha_inicio)) {
                    return false;
                }
                if ($periodo && $fecha->greaterThan($periodo->fecha_fin)) {
                    return false;
                }

                return true;
            });

            foreach ($matriculas as $matricula) {
                foreach ($fechasClase as $fecha) {
                    $estado = $this->estadoAleatorio();

                    Asistencia::create([
                        'alumno_id' => $matricula->alumno_id,
                        'asignacion_id' => $asignacion->id,
                        'fecha' => $fecha->format('Y-m-d'),
                        'estado' => $estado,
                        'observacion' => in_array($estado, [AsistenciaEstado::Ausente, AsistenciaEstado::Justificado])
                            ? fake()->optional(0.5)->sentence()
                            : null,
                    ]);
                }
            }
        }
    }

    private function ultimosDiasHabiles(Carbon $fechaFin, int $cantidad): array
    {
        $dias = [];
        $fecha = $fechaFin->copy();

        while (count($dias) < $cantidad) {
            if ($fecha->dayOfWeekIso <= 5) {
                $dias[] = $fecha->copy();
            }
            $fecha->subDay();
        }

        return $dias;
    }

    private function estadoAleatorio(): AsistenciaEstado
    {
        $rand = mt_rand(1, 100);

        if ($rand <= 88) {
            return AsistenciaEstado::Presente;
        }
        if ($rand <= 92) {
            return AsistenciaEstado::Tardanza;
        }
        if ($rand <= 97) {
            return AsistenciaEstado::Ausente;
        }

        return AsistenciaEstado::Justificado;
    }
}
