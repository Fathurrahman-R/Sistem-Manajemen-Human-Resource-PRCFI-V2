<?php

namespace App\Filament\Tables;

use App\Enum\Permission as PermissionEnum;
use App\Enum\Colors;
use Filament\Actions\BulkActionGroup;
use Filament\Support\Enums\TextSize;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Spatie\Permission\Models\Permission;

class PermissionTableResource
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->paginated(false)
            ->modifyQueryUsing(fn (Builder $query) => $query
                ->whereNotIn('permissions.group', ['Manajemen User', 'Manajemen Role']))
            ->columns([
                TextColumn::make('name')
                    ->alignCenter()
                    ->size(TextSize::Large)
                    ->badge()
                    ->color(fn ($record) => PermissionEnum::tryFrom($record->name)?->getColor())
                    ->searchable(),
            ])
            ->groups([
                Group::make('group')
                    ->titlePrefixedWithLabel(false)
                    ->collapsible()
            ])
            ->groupingSettingsHidden()
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
