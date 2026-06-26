<?php

namespace App\Services;

use App\Enums\NotificacionTipo;
use App\Models\Alumno;
use App\Models\Asistencia;
use App\Models\IncidenciaConducta;
use App\Models\Nota;
use App\Models\Notificacion;
use Illuminate\Support\Str;

class NotificacionService
{
    public function notificarInasistencia(Alumno $alumno, Asistencia $asistencia): void
    {
        $padres = $alumno->padres;

        foreach ($padres as $padre) {
            if (! $padre->user_id) {
                continue;
            }

            Notificacion::create([
                'user_id' => $padre->user_id,
                'tipo' => NotificacionTipo::Inasistencia,
                'mensaje' => "{$alumno->nombres} {$alumno->apellido_paterno} registró {$asistencia->estado->label()} el {$asistencia->fecha->format('d/m/Y')}",
                'leido' => false,
            ]);
        }
    }

    public function notificarNotaCritica(Alumno $alumno, Nota $nota): void
    {
        $padres = $alumno->padres;

        foreach ($padres as $padre) {
            if (! $padre->user_id) {
                continue;
            }

            Notificacion::create([
                'user_id' => $padre->user_id,
                'tipo' => NotificacionTipo::NotaCritica,
                'mensaje' => "{$alumno->nombres} {$alumno->apellido_paterno} obtuvo calificación {$nota->calificacion->value} en {$nota->actividad->titulo}",
                'leido' => false,
            ]);
        }
    }

    public function notificarIncidencia(Alumno $alumno, IncidenciaConducta $incidencia): void
    {
        $padres = $alumno->padres;

        foreach ($padres as $padre) {
            if (! $padre->user_id) {
                continue;
            }

            Notificacion::create([
                'user_id' => $padre->user_id,
                'tipo' => NotificacionTipo::IncidenciaConducta,
                'mensaje' => "Se registró {$incidencia->tipo->label()} para {$alumno->nombres} {$alumno->apellido_paterno}: ".Str::limit($incidencia->descripcion, 80),
                'leido' => false,
            ]);
        }
    }
}
