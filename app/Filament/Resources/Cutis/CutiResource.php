<?php

namespace App\Filament\Resources\Cutis;

use App\Enum\Cuti\StatusPengajuan;
use App\Enum\Role;
use App\Filament\Resources\Cutis\Pages\CreateCuti;
use App\Filament\Resources\Cutis\Pages\EditCuti;
use App\Filament\Resources\Cutis\Pages\ListCutis;
use App\Filament\Resources\Cutis\Schemas\CutiForm;
use App\Filament\Resources\Cutis\Tables\CutisTable;
use App\Models\Cuti;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class CutiResource extends Resource
{
    protected static ?string $model = Cuti::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Ticket;
    protected static ?string $navigationLabel = 'Pengajuan Cuti';

    protected static ?string $recordTitleAttribute = 'Manajemen Cuti';

    public static function form(Schema $schema): Schema
    {
        return CutiForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CutisTable::configure($table);
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
            'index' => ListCutis::route('/'),
            'create' => CreateCuti::route('/create'),
            'edit' => EditCuti::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = Auth::user();

        if ($user->hasRole(Role::DIREKTUR)) {
            return $query->where('status', StatusPengajuan::MenungguDirektur->value);
        }

        if ($user->hasRole(Role::ADMIN)) {
            return $query->whereIn('status', [
                StatusPengajuan::Diajukan->value,
                StatusPengajuan::MenungguHR->value
            ]);
        }

        // Karyawan hanya bisa lihat cuti milik sendiri
        if ($user->hasRole(Role::KARYAWAN)) {
            $karyawanId = \App\Models\User::getKaryawanId($user);
            return $query->where('karyawan_id', $karyawanId);
        }

        return $query;
    }

}
