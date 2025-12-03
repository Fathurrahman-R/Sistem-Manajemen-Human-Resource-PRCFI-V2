<?php

namespace App\Filament\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Support\Enums\TextSize;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\Models\Permission;

class PermissionTableResource
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Permission::query())
            ->columns([
                TextColumn::make('name')
                    ->alignCenter()
                    ->size(TextSize::Large)
                    ->badge()
                    ->searchable(),
            ])
            ->defaultGroup('group')
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                //
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}
