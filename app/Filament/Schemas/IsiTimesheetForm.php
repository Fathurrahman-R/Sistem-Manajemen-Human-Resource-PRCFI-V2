<?php

namespace App\Filament\Schemas;

use App\Enum\Timesheet\Location;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\FusedGroup;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use IsiTimesheet;

class IsiTimesheetForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                ToggleButtons::make('location')
                    ->required()
                    ->hiddenLabel()
                    ->options(Location::class)
                    ->inline(),
                Group::make([
                    FusedGroup::make([
                        DatePicker::make('tanggal')
                            ->required()
                            ->prefixIcon(Heroicon::Calendar)
                            ->displayFormat('d M Y')
                            ->label(__('Date'))
                            ->default(now())
                            ->native(false),
                        TextInput::make('place')
                            ->required()
                            ->prefixIcon(Heroicon::MapPin)
                            ->columnSpan(2),
                    ])
                        ->label('Date and place')
                        ->columns(3)
                        ->columnSpan(2),
                    ToggleButtons::make('jam_bekerja')
                        ->required()
                        ->inline()
                        ->options([8=>'Satu hari',4=>'Setengah hari']),
                ])->columns(3),
                Textarea::make('work_done')
                    ->required()
            ])->columns(1);
    }
}
