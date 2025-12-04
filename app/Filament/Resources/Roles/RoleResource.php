<?php

namespace App\Filament\Resources\Roles;

use App\Filament\Resources\Roles\Pages\ManageRoles;
use App\Filament\Tables\PermissionTableResource;
use App\Models\Cuti;
use App\Models\Role;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\ModalTableSelect;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Enums\TextSize;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use UnitEnum;

class RoleResource extends Resource
{
    protected static ?string $model = \Spatie\Permission\Models\Role::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static ?string $recordTitleAttribute = 'name';
    protected static string | UnitEnum | null $navigationGroup = 'Authorization';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama role')
                    ->unique()
                    ->required()
                    ->maxLength(255),
                ModalTableSelect::make('permission')
                    ->multiple()
                    ->tableConfiguration(PermissionTableResource::class)
                    ->relationship('permissions','name')
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Manajemen Role')
            ->columns([
                TextColumn::make('name')
                    ->size(TextSize::Large)
                    ->badge()
                    ->searchable()
                    ->color(fn($state):string => match ($state){
                        \App\Enum\Role::SUPERADMIN->value => 'danger',
                        \App\Enum\Role::ADMIN->value => 'warning',
                        \App\Enum\Role::DIREKTUR->value => 'primary',
                        \App\Enum\Role::KARYAWAN->value => 'success',
                        default => 'gray'
                    }),
                TextColumn::make('permissions.name')
                    ->size(TextSize::Large)
                    ->alignCenter()
                    ->wrap()
                    ->badge()
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()
                    ->hidden(fn($record)=> $record->name === \App\Enum\Role::SUPERADMIN->value),
                DeleteAction::make()
                    ->hidden(fn($record)=> $record->name === \App\Enum\Role::SUPERADMIN->value || $record->name === \App\Enum\Role::ADMIN->value),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageRoles::route('/'),
        ];
    }
}
