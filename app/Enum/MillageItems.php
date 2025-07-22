<?php

namespace App\Enum;

use Filament\Support\Contracts\HasLabel;

enum MillageItems: string implements HasLabel
{
    case M_7500 = '7500';
    case M_15000 = '15000';
    case M_22500 = '22500';
    case M_30000 = '30000';
    case M_37500 = '37500';
    case M_45000 = '45000';
    case M_52500 = '52500';
    case M_60000 = '60000';
    case M_67500 = '67500';
    case M_75000 = '75000';
    case M_82500 = '82500';
    case M_90000 = '90000';
    case M_97500 = '97500';
    case M_105000 = '105000';
    case M_112500 = '112500';
    case M_120000 = '120000';
    case M_127500 = '127500';
    case M_135000 = '135000';
    case M_150000 = '150000';
    case M_157500 = '157500';
    case M_165000 = '165000';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::M_7500 => '7500 Km',
            self::M_15000 => '15000 km',
            self::M_22500 => '22500 km',
            self::M_30000 => '30000 km',
            self::M_37500 => '37500 km',
            self::M_45000 => '45000 km',
            self::M_52500 => '52500 km',
            self::M_60000 => '60000 km',
            self::M_67500 => '67500 km',
            self::M_75000 => '75000 km',
            self::M_82500 => '82500 km',
            self::M_90000 => '90000 km',
            self::M_97500 => '97500 km',
            self::M_105000 => '105000 km',
            self::M_112500 => '112500 km',
            self::M_120000 => '120000 km',
            self::M_127500 => '127500 km',
            self::M_135000 => '135000 km',
            self::M_150000 => '150000 km',
            self::M_157500 => '157500 km',
            self::M_165000 => '165000 km',
        };
    }
}
