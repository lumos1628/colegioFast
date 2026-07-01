<?php

namespace App\Enums;

enum UserRole: string
{
    case Director = 'director';
    case Docente = 'docente';
    case Alumno = 'alumno';
    case Padre = 'padre';
    case Psicologo = 'psicologo';

    public function label(): string
    {
        return match ($this) {
            self::Director => 'Director',
            self::Docente => 'Docente',
            self::Alumno => 'Alumno',
            self::Padre => 'Padre',
            self::Psicologo => 'Psicólogo',
        };
    }

    public function redirectRoute(): string
    {
        return match ($this) {
            self::Director => 'director',
            self::Docente => 'docente.dashboard',
            self::Alumno => 'alumno.dashboard',
            self::Padre => 'padre.dashboard',
            self::Psicologo => 'psicologo.dashboard',
        };
    }
}
