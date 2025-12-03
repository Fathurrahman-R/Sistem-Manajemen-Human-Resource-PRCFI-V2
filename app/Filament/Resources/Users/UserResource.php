<?php

namespace App\Filament\Resources\Users;

use App\Filament\Resources\Users\Pages\ManageUsers;
use App\Models\User;
use App\Permissions\Permission;
use BackedEnum;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use UnitEnum;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUser;
    protected static ?string $recordTitleAttribute = 'Manajemen User';
    protected static string | UnitEnum | null $navigationGroup = 'Authorization';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                Select::make('role')
                    ->native(false)
                    ->disableOptionWhen(fn (string $value): bool => $value === \App\Enum\Role::SUPERADMIN->value)
                    ->relationship(name: 'roles', titleAttribute: 'name', modifyQueryUsing: fn(Builder $query): Builder => $query->whereNotIn('name', [\App\Enum\Role::SUPERADMIN]))
                    ->required(),
                TextInput::make('password')
                    ->password()
                    ->required(fn ($context):bool => $context === 'create'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('Daftar User')
                    ->hiddenFrom('md')
                    ->html()
                    ->getStateUsing(fn ($record) => "<strong>{$record->name}</strong><br>{$record->email}<br><span class='fi-color fi-color-primary fi-text-color-600 dark:fi-text-color-200 fi-badge fi-size-sm'>{$record->roles->pluck('name')->first()}</span>")
                    ->searchable(),
                TextColumn::make('name')
                    ->visibleFrom('md')
                    ->weight(FontWeight::Bold)
                    ->searchable(),
                TextColumn::make('email')
                    ->visibleFrom('md')
                    ->icon(Heroicon::Envelope)
                    ->label('Email address')
                    ->searchable(),
                TextColumn::make('roles.name')
                    ->size(TextSize::Large)
                    ->visibleFrom('md')
                    ->alignCenter()
                    ->label('Role')
                    ->badge()
                    ->color(fn($state):string => match ($state){
                        \App\Enum\Role::SUPERADMIN->value => 'danger',
                        \App\Enum\Role::ADMIN->value => 'warning',
                        \App\Enum\Role::DIREKTUR->value => 'primary',
                        \App\Enum\Role::KARYAWAN->value => 'success',
                        default => 'gray'
                    }),
                TextColumn::make('email_verified_at')
                    ->visibleFrom('md')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make()
                        ->mutateDataUsing(function (array $data,$record):array {
                            if(blank($data['password'])??null) {
                                unset($data['password']);
                            }
                            else
                            {
                                $data['password'] = Hash::make($data['password']);
                            }
                            return $data;
                        })
                        ->visible(fn ($record):bool => !$record->hasRole(\App\Enum\Role::SUPERADMIN)),
                    DeleteAction::make()
                        ->visible(fn ($record):bool => !$record->hasRole(\App\Enum\Role::SUPERADMIN)),
                ])
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])->poll('30s');
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageUsers::route('/'),
        ];
    }
}
