<?php

namespace App\Enum\Cuti;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum StatusPengajuan:string implements HasColor,HasLabel
{
    case Diajukan = 'Diajukan';
    case MenungguHR = 'Menunggu Persetujuan HR';
    case MenungguDirektur = 'Menunggu Persetujuan Direktur';
    case Disetujui = 'Disetujui';
    case Ditolak = 'Ditolak';

    public function getLabel(): string|Htmlable|null
    {
        return $this->value;
    }
    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Diajukan => 'gray',
            self::MenungguHR => 'primary',
            self::MenungguDirektur => 'warning',
            self::Disetujui => 'success',
            self::Ditolak => 'danger',
        };
    }
}
