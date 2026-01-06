<?php

namespace App\Filament\Schemas;

use App\Enum\Timesheet\DefaultPlacePerformance;
use App\Enum\Timesheet\Location;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\FusedGroup;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use App\Models\IsiTimesheet;

class IsiTimesheetForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                ToggleButtons::make('location')
                    ->options(Location::options())
                    ->reactive()
                    ->afterStateUpdated(function ($state, $set) {
                        if ($state === Location::Weekends->getLabel())
                        {
                            $set('work_done', Location::Weekends->getLabel());
                        }else{
                            $state = match ($state) {
                                Location::Sintang->getLabel() => DefaultPlacePerformance::Sintang,
                                Location::KapuasHulu->getLabel() => DefaultPlacePerformance::KapuasHulu,
                                Location::Pontianak->getLabel() => DefaultPlacePerformance::Pontianak,
                                default => null,
                            };
                            $set('place', $state??null);
                        }
                    })
                    ->required()
                    ->hiddenLabel()
                    ->inline(),
                Group::make([
                    FusedGroup::make([
                        DatePicker::make('tanggal')
                            ->closeOnDateSelection()
                            ->disabledDates(function (RelationManager $livewire) {
//                                $dates = [];
//
//                                $tanggal = $livewire->getOwnerRecord()->tanggal;
//                                $period = CarbonPeriod::create(
//                                    Carbon::parse($tanggal)->startOfMonth()->startOfDay(),
//                                    Carbon::parse($tanggal)->endOfMonth()->endOfDay()
//                                );
//                                foreach ($period as $date) {
//                                    if ($date->isWeekend()) {
//                                        $dates[] = $date->toDateString(); // YYYY-MM-DD
//                                    }
//                                }

//                                return $dates;
                                return \App\Models\IsiTimesheet::query()
                                    ->select('tanggal')
                                    ->where('timesheet_id', $livewire->getOwnerRecord()->id)
                                    ->pluck('tanggal')
                                    ->map(fn ($date) => $date->format('Y-m-d'))
                                    ->toArray();
                            })
                            ->required()
                            ->prefixIcon(Heroicon::Calendar)
                            ->displayFormat('d F Y')
                            ->label(__('Date'))
                            ->minDate(fn(RelationManager $livewire)=>Carbon::parse($livewire->getOwnerRecord()->tanggal)->startOfMonth()->startOfDay())
                            ->maxDate(fn(RelationManager $livewire)=>Carbon::parse($livewire->getOwnerRecord()->tanggal)->endOfMonth()->endOfDay())
                            ->default(now())
                            ->native(false),
                        TextInput::make('place')
                            ->minLength(3)
                            ->maxLength(100)
                            ->placeholder('Lokasi kerja')
                            ->required()
                            ->prefixIcon(Heroicon::MapPin)
                    ])
                        ->label('Date and place')
                        ->columns(2)
                        ->columnSpan(2),
                    ToggleButtons::make('jam_bekerja')
                        ->required()
                        ->inline()
                        ->options([8=>'Satu hari',4=>'Setengah hari',1=>'Weekend']),
                ])->columns(3),
                Textarea::make('work_done')
                    ->placeholder('Lampirkan pekerjaan yang diselesaikan disini.')
                    ->required()
            ])->columns(1);
    }
}
