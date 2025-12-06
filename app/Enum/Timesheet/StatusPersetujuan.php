<?php

namespace App\Enum\Timesheet;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum StatusPersetujuan: string implements HasLabel, HasColor
{
    case Dibuat = 'Dibuat';
    case Dilihat = 'Dilihat';
    case Diteruskan = 'Diteruskan';
    case Approved = 'Disetujui';
    case Rejected = 'Ditolak';

    public function getLabel(): string|Htmlable|null
    {
        return $this->value;
    }
    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Dibuat => 'gray',
            self::Dilihat => 'primary',
            self::Diteruskan => 'warning',
            self::Approved => 'success',
            self::Rejected => 'danger',
        };
    }
}
