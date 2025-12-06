<?php

namespace App\Enum\Timesheet;

enum LocationCellMapping: string
{
    case PresentAtWork = 'B12';
    case Pontianak = 'B13';
    case KapuasHulu = 'B14';
    case Sintang = 'B15';
    case Travel = 'B16';
    case Other = 'B17';
    case Weekends = 'B18';
    case OfficialHolidays = 'B19';
    case Leave = 'B20';
    case SickLeave = 'B21';
    case AnnualHolidays = 'B22';
}
