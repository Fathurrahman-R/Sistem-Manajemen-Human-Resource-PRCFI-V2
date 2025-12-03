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
                    ->unique()
                    ->required()
                    ->maxLength(100),
                TextInput::make('lokasi')
                    ->maxLength(100)
                    ->required(),
                DatePicker::make('tanggal_mulai')
                    ->native(false)
                    ->displayFormat('d M Y')
                    ->required(),
                DatePicker::make('tanggal_selesai')
                    ->native(false)
                    ->displayFormat('d M Y')
                    ->required()
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nama')
            ->columns([
                TextColumn::make('nama')
                    ->searchable(),
                TextColumn::make('lokasi'),
                TextColumn::make('tanggal_mulai')
                    ->date('d M Y'),
                TextColumn::make('tanggal_selesai')
                    ->date('d M Y')
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
