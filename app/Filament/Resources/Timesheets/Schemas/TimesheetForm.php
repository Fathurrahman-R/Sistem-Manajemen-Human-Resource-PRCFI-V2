<?php

namespace App\Filament\Resources\Timesheets\Schemas;

use App\Enum\Timesheet\StatusPersetujuan;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TimesheetForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
//                TextInput::make('karyawan.nama_lengkap')->readOnly(),
                TextEntry::make('karyawan.nama_lengkap')->visible(fn($operation)=>$operation=='view'),
                TextEntry::make('karyawan.posisi')->visible(fn($operation)=>$operation=='view'),
                TextEntry::make('karyawan.unit_kerja')->visible(fn($operation)=>$operation=='view'),
                DatePicker::make('tanggal')
                    ->readOnly(fn($operation)=>$operation=='view'||$operation=='edit')
                    ->displayFormat('F Y')
                    ->minDate(\Illuminate\Support\now()->endOfMonth())
                    ->maxDate(\Illuminate\Support\now()->endOfMonth())
                    ->default(\Illuminate\Support\now()->endOfMonth())
                    ->native(false)
                    ->required(),
            ]);
    }
}
