<?php

namespace App\Enums;

enum IncidenciaTipo: string
{
    case FaltaLeve = 'falta_leve';
    case FaltaGrave = 'falta_grave';
    case Merito = 'merito';

    public function label(): string
    {
        return match ($this) {
            self::FaltaLeve => 'Falta leve',
            self::FaltaGrave => 'Falta grave',
            self::Merito => 'Mérito',
        };
    }
}
