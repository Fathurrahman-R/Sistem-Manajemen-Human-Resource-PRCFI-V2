<?php

namespace App\Enum\Master;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum EnglishSkill:string implements HasColor,HasLabel
{
    case Low = 'Low';
    case Medium = 'Medium';
    case High = 'High';

    public function getLabel(): string|Htmlable|null
    {
        return $this->value;
    }
    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Low => 'danger',
            self::Medium => 'warning',
            self::High => 'success',
        };
    }
}
