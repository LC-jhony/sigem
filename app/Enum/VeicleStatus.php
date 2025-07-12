<?php

namespace App\Enum;

use Filament\Support\Contracts\HasLabel;

enum VeicleStatus: string implements HasLabel
{
    case Operativo = 'Operativo';
    case EnMantenimiento = 'En Mantenimiento';
    case FueraDeServicio = 'Fuera de Servicio';
    case EnReparacion = 'En Reparación';
    case Disponible = 'Disponible';
    case EnUso = 'En Uso';
    public function getLabel(): ?string
    {
        return match ($this) {
            self::Operativo => 'Operativo',
            self::EnMantenimiento => 'En Mantenimiento',
            self::FueraDeServicio => 'Fuera de Servicio',
            self::EnReparacion => 'En Reparación',
            self::Disponible => 'Disponible',
            self::EnUso => 'En Uso',
        };
    }
}
