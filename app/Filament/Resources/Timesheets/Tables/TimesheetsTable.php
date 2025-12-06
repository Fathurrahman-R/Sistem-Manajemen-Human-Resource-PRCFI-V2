<?php

namespace App\Filament\Resources\Timesheets\Tables;

use App\Models\Timesheet;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use PHPUnit\Metadata\Group;

class TimesheetsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('karyawan.nama_lengkap')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('tanggal')
                    ->date()
                    ->sortable(),
                TextColumn::make('status'),
            ])->defaultGroup(\Filament\Tables\Grouping\Group::make('tanggal')
                ->groupQueryUsing(fn(Builder $q)=>$q->select(DB::raw('MONTHNAME(tanggal) as bulan'), DB::raw('YEAR(tanggal) as tahun'))
                    ->groupBy('bulan')
                    ->groupBy('tahun'))->date())
            ->filters([
                //
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make()->label(''),
                    EditAction::make()->label('')->color('gray'),
                    DeleteAction::make()->label(''),
                ])->buttonGroup()
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->authorize('delete'),
                ]),
            ]);
    }
}
