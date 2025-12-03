<?php

namespace App\Filament\Resources\Master\Karyawans;

use App\Enum\Role;
use App\Filament\Resources\Master\Karyawans\Pages\CreateKaryawan;
use App\Filament\Resources\Master\Karyawans\Pages\EditKaryawan;
use App\Filament\Resources\Master\Karyawans\Pages\ListKaryawans;
use App\Filament\Resources\Master\Karyawans\Pages\ViewKaryawan;
use App\Filament\Resources\Master\Karyawans\RelationManagers\ProgramRelationManager;
use App\Filament\Resources\Master\Karyawans\Schemas\KaryawanForm;
use App\Filament\Resources\Master\Karyawans\Schemas\KaryawanInfolist;
use App\Filament\Resources\Master\Karyawans\Tables\KaryawansTable;
use App\Models\Master\Karyawan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class KaryawanResource extends Resource
{
    protected static ?string $model = Karyawan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?string $recordTitleAttribute = 'Data Master Karyawan';
    protected static string | UnitEnum | null $navigationGroup = 'Data Master';

    public static function form(Schema $schema): Schema
    {
        return KaryawanForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return KaryawanInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KaryawansTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            ProgramRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListKaryawans::route('/'),
            'create' => CreateKaryawan::route('/create'),
            'view' => ViewKaryawan::route('/{record}'),
            'edit' => EditKaryawan::route('/{record}/edit'),
        ];
    }
    public static function getEloquentQuery(): Builder
    {
        $query =  parent::getEloquentQuery();
        return $query->whereNotIn('posisi', ['Superadmin']);
    }

}
