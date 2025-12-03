<?php

namespace App\Enum\Master;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum StatusKerja:string implements HasLabel,HasColor
{
    case Kontrak = 'Kontrak';
    case Tetap = 'Tetap';
    case Resign = 'Resign';
    public function getLabel(): string|Htmlable|null
    {
        return $this->value;
    }
    public function getColor(): string|array|null
    {
        return match ($this){
            self::Kontrak => 'warning',
            self::Tetap => 'success',
            self::Resign => 'danger',
        };
    }
}
