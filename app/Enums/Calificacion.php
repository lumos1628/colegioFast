<?php

namespace App\Enums;

enum Calificacion: string
{
    case AD = 'AD';
    case A = 'A';
    case B = 'B';
    case C = 'C';

    public function label(): string
    {
        return match ($this) {
            self::AD => 'Logro destacado',
            self::A => 'Logro esperado',
            self::B => 'En proceso',
            self::C => 'En inicio',
        };
    }

    public function numericValue(): int
    {
        return match ($this) {
            self::AD => 4,
            self::A => 3,
            self::B => 2,
            self::C => 1,
        };
    }
}
