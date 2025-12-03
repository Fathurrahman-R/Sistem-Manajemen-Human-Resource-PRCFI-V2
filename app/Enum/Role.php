<?php

namespace App\Enum;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum Role:string implements HasLabel, HasColor
{
    case SUPERADMIN = 'Superadmin';
    case ADMIN = 'Admin';
    case DIREKTUR = 'Direktur';
    case KARYAWAN = 'Karyawan';

    public function getLabel(): string|Htmlable|null
    {
        return $this->value;
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::SUPERADMIN => 'danger',
            self::DIREKTUR => 'primary',
            self::ADMIN => 'warning',
            self::KARYAWAN => 'success',
        };
    }
}
