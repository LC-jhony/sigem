<?php

namespace App\Enum;

use Filament\Support\Contracts\HasLabel;

enum DocumentName: string implements HasLabel
{
    case SOAT = 'SOAT';
    case TARJETA_DE_CIRCULACION = 'TARJETA DE CIRCULACION';
    case REVICION_TECNICA = 'REVICION TECNICA';
    case POLIZA_DE_SEGURO_VEHICULAR = 'POLIZA DE SEGURO VEHICULAR';
    public function getLabel(): ?string
    {
        return match ($this) {
            self::SOAT => 'SOAT',
            self::TARJETA_DE_CIRCULACION => 'TARJETA DE CIRCULACION',
            self::REVICION_TECNICA => 'REVICION TECNICA',
            self::POLIZA_DE_SEGURO_VEHICULAR => 'POLIZA DE SEGURO VEHICULAR',
        };
    }
}
