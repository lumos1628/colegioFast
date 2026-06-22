<?php

namespace App\Enums;

enum NotificacionTipo: string
{
    case Inasistencia = 'inasistencia';
    case NotaCritica = 'nota_critica';
    case IncidenciaConducta = 'incidencia_conducta';
    case TareaPendiente = 'tarea_pendiente';

    public function label(): string
    {
        return match ($this) {
            self::Inasistencia => 'Inasistencia',
            self::NotaCritica => 'Nota crítica',
            self::IncidenciaConducta => 'Incidencia de conducta',
            self::TareaPendiente => 'Tarea pendiente',
        };
    }
}
