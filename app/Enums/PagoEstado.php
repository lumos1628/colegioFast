<?php

namespace App\Enums;

enum PagoEstado: string
{
    case Pagado = 'pagado';
    case Pendiente = 'pendiente';
    case Vencido = 'vencido';

    public function label(): string
    {
        return match ($this) {
            self::Pagado => 'Pagado',
            self::Pendiente => 'Pendiente',
            self::Vencido => 'Vencido',
        };
    }
}
