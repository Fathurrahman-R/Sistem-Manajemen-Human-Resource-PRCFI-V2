<?php

namespace App\Enum\Master;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum RiwayatPendidikan:string implements HasLabel
{
    case SD = 'SD';
    case SMP = 'SMP';
    case SMA = 'SMA';
    case D1 = 'D1';
    case D2 = 'D2';
    case D3 = 'D3';
    case D4 = 'D4';
    case S1 = 'S1';
    case S2 = 'S2';
    case S3 = 'S3';
    public function getLabel(): string|Htmlable|null
    {
        return $this->value;
    }
}
