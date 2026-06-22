<?php

namespace App\Enums;

enum Grado: int
{
    case Primero = 1;
    case Segundo = 2;
    case Tercero = 3;
    case Cuarto = 4;
    case Quinto = 5;
    case Sexto = 6;

    public function label(): string
    {
        return match ($this) {
            self::Primero => '1°',
            self::Segundo => '2°',
            self::Tercero => '3°',
            self::Cuarto => '4°',
            self::Quinto => '5°',
            self::Sexto => '6°',
        };
    }
}
