<?php

namespace App\Filament\Resources\Master\Karyawans\RelationManagers;

use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Support\Enums\TextSize;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProgramRelationManager extends RelationManager
{
    protected static string $relationship = 'program';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama')
                    ->prefixIcon(Heroicon::Star)
                    ->minLength(2)
                    ->maxLength(100)
                    ->placeholder('Nama Program')
                    ->required(),
                TextInput::make('lokasi')
                    ->prefixIcon(Heroicon::MapPin)
                    ->minLength(3)
                    ->maxLength(100)
                    ->placeholder('Lokasi Pelaksanaan')
                    ->required(),
                DatePicker::make('tanggal_mulai')
                    ->prefixIcon(Heroicon::CalendarDateRange)
                    ->closeOnDateSelection()
                    ->minDate(now()->subYears(90)->startOfDay())
                    ->maxDate(now()->addYears(5)->startOfDay())
                    ->default(now())
                    ->displayFormat('d F Y')
                    ->native(false)
                    ->required(),
                DatePicker::make('tanggal_selesai')
                    ->prefixIcon(Heroicon::CalendarDateRange)
                    ->closeOnDateSelection()
                    ->minDate(now()->subYears(90)->startOfDay())
                    ->maxDate(now()->addYears(10)->startOfDay())
                    ->default(now()->addMonths(1)->startOfDay())
                    ->displayFormat('d F Y')
                    ->native(false)
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nama')
            ->columns([
                TextColumn::make('nama')
                    ->badge()
                    ->size(TextSize::Large)
                    ->searchable(),
                TextColumn::make('lokasi')
                    ->visibleFrom('md')
                    ->searchable(),
                TextColumn::make('tanggal_mulai')
                    ->visibleFrom('md')
                    ->date('d F Y')
                    ->sortable(),
                TextColumn::make('tanggal_selesai')
                    ->visibleFrom('md')
                    ->date('d F Y')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
                AttachAction::make()->preloadRecordSelect(),
            ])
            ->recordActions([
                EditAction::make(),
                DetachAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DetachBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
