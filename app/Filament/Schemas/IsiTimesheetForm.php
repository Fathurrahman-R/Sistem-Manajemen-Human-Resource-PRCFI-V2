<?php

namespace App\Filament\Schemas;

use App\Enum\Timesheet\Location;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Schema;
use IsiTimesheet;

class IsiTimesheetForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                ToggleButtons::make('location')->options(Location::class)->inline(),
                DatePicker::make('tanggal')->label(__('Date'))
                    ->native(false),
                ToggleButtons::make('jam_bekerja')->options([8=>'Satu hari',4=>'Setengah hari']),
                TextInput::make('place'),
                TextInput::make('work_done')
            ]);
    }
}
