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

    public function color(): string
    {
        return match ($this) {
            self::Presente => 'bg-green-100 text-green-800',
            self::Tardanza => 'bg-yellow-100 text-yellow-800',
            self::Ausente => 'bg-red-100 text-red-800',
            self::Justificado => 'bg-blue-100 text-blue-800',
        };
    }
}
