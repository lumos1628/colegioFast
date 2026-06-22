<?php

namespace App\Enums;

enum AsistenciaEstado: string
{
    case Presente = 'presente';
    case Tardanza = 'tardanza';
    case Ausente = 'ausente';
    case Justificado = 'justificado';

    public function label(): string
    {
        return match ($this) {
            self::Presente => 'Presente',
            self::Tardanza => 'Tardanza',
            self::Ausente => 'Ausente',
            self::Justificado => 'Justificado',
        };
    }
}
