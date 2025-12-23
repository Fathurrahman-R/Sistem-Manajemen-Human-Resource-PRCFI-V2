<?php

namespace App\Enum\Timesheet;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum DefaultPlacePerformance: string implements HasLabel
{
    case Pontianak = 'Pontianak';
    case KapuasHulu = 'Kapuas Hulu';
    case Sintang = 'Sintang';

    public function getLabel(): string|Htmlable|null
    {
        return $this->value;
    }
}
