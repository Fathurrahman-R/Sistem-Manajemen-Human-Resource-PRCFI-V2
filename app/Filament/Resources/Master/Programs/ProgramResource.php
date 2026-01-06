<?php

namespace App\Filament\Resources\Master\Programs;

use App\Filament\Resources\Master\Programs\Pages\ManagePrograms;
use App\Filament\Resources\Master\Programs\RelationManagers\KaryawanRelationManager;
use App\Models\Master\Program;
use BackedEnum;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Enums\TextSize;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class ProgramResource extends Resource
{
    protected static ?string $model = Program::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedStar;

    protected static ?string $recordTitleAttribute = 'nama';
    protected static string | UnitEnum | null $navigationGroup = 'Data Master';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama')
                    ->prefixIcon(Heroicon::Star)
                    ->minLength(2)
                    ->maxLength(100)
                    ->placeholder("Nama Program")
                    ->required(),
                TextInput::make('lokasi')
                    ->prefixIcon(Heroicon::MapPin)
                    ->minLength(3)
                    ->maxLength(100)
                    ->placeholder("Lokasi Pelaksanaan")
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

    public static function table(Table $table): Table
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
            ->recordActions([
                ActionGroup::make([
                    EditAction::make()->label('')->color('gray'),
                    DeleteAction::make()->label(''),
                ])->buttonGroup()
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ManagePrograms::route('/'),
        ];
    }
}
