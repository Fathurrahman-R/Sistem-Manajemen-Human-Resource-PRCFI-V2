<?php

namespace App\Filament\Resources\Timesheets;

use App\Enum\Cuti\StatusPengajuan;
use App\Enum\Permission;
use App\Enum\Role;
use App\Enum\Timesheet\StatusPersetujuan;
use App\Filament\Resources\Timesheets\Pages\CreateTimesheet;
use App\Filament\Resources\Timesheets\Pages\EditTimesheet;
use App\Filament\Resources\Timesheets\Pages\ListTimesheets;
use App\Filament\Resources\Timesheets\Pages\ViewTimesheet;
use App\Filament\Resources\Timesheets\RelationManagers\IsiTimesheetRelationManager;
use App\Filament\Resources\Timesheets\Schemas\TimesheetForm;
use App\Filament\Resources\Timesheets\Tables\TimesheetsTable;
use App\Models\Timesheet;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class TimesheetResource extends Resource
{
    protected static ?string $model = Timesheet::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDateRange;

    protected static ?string $recordTitleAttribute = 'karyawan.nama_lengkap';

    public static function form(Schema $schema): Schema
    {
        return TimesheetForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TimesheetsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            IsiTimesheetRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTimesheets::route('/'),
            'create' => CreateTimesheet::route('/create'),
            'view' => ViewTimesheet::route('/{record}'),
            'edit' => EditTimesheet::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = Auth::user();

        if ($user->hasAnyPermission(Permission::APPROVE_MANAGE_TIMESHEET, Permission::REJECT_MANAGE_TIMESHEET)) {
            return $query->where('status', StatusPersetujuan::Diteruskan);
        }

        if ($user->hasPermissionTo(Permission::DIRECT_MANAGE_TIMESHEET)) {
            return $query->whereIn('status', [
                StatusPersetujuan::Dibuat,
                StatusPersetujuan::Dilihat
            ]);
        }

        // Karyawan hanya bisa lihat cuti milik sendiri
        if ($user->hasAnyPermission(Permission::EDIT_MANAGE_TIMESHEET,Permission::DELETE_MANAGE_TIMESHEET,Permission::CREATE_MANAGE_TIMESHEET)) {
            $karyawanId = \App\Models\User::getKaryawanId($user);
            return $query->where('karyawan_id', $karyawanId);
        }

        return $query;
    }
}
