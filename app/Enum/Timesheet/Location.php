<?php

namespace App\Enum\Timesheet;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum Location: string implements HasLabel
{
    case Pontianak = 'Pontianak office';
    case KapuasHulu = 'Location Kapuas Hulu';
    case Sintang = 'Location Sintang';
    case Travel = 'Official Travel';
    case Other = 'Official Work (Other)';
    case Weekends = 'Weekends (Sat/Sun)';
    case OfficialHolidays = 'Official holidays';
    case Leave = 'Leave due with approved supervisor';
    case SickLeave = 'Sick leave';
    case AnnualHolidays = 'Annual Holidays';

    public function getLabel(): string|Htmlable|null
    {
        return $this->value;
    }
}
