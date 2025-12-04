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
                    ->required(),
                TextInput::make('lokasi')
                    ->required(),
                DatePicker::make('tanggal_mulai')
                    ->native(false)
                    ->required(),
                DatePicker::make('tanggal_selesai')
                    ->native(false)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Manajemen Program')
            ->columns([
                TextColumn::make('nama')
                    ->searchable(),
                TextColumn::make('lokasi')
                    ->visibleFrom('md')
                    ->searchable(),
                TextColumn::make('tanggal_mulai')
                    ->visibleFrom('md')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('tanggal_selesai')
                    ->visibleFrom('md')
                    ->date('d M Y')
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
