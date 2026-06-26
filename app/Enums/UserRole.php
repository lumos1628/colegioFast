<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case Director = 'director';
    case Secretaria = 'secretaria';
    case Docente = 'docente';
    case Alumno = 'alumno';
    case Padre = 'padre';
    case Psicologo = 'psicologo';

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Administrador',
            self::Director => 'Director',
            self::Secretaria => 'Secretaría',
            self::Docente => 'Docente',
            self::Alumno => 'Alumno',
            self::Padre => 'Padre',
            self::Psicologo => 'Psicólogo',
        };
    }

    public function redirectRoute(): string
    {
        return match ($this) {
            self::Admin => 'admin',
            self::Director => 'director',
            self::Secretaria => 'secretaria',
            self::Docente => 'docente.dashboard',
            self::Alumno => 'alumno.dashboard',
            self::Padre => 'padre.dashboard',
            self::Psicologo => 'psicologo.dashboard',
        };
    }
}
