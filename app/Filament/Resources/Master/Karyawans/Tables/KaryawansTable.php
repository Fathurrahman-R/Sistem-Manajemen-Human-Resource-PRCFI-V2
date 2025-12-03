<?php

namespace App\Filament\Resources\Master\Karyawans\Tables;

use App\Enum\Master\StatusKerja;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class KaryawansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('Daftar Karyawan')
                    ->hiddenFrom('md')
                    ->html()
                    ->getStateUsing(fn ($record) => "<strong>{$record->nama_lengkap}</strong><br>{$record->email}<br><span class='fi-color fi-color-primary fi-text-color-600 dark:fi-text-color-200 fi-badge fi-size-sm'>{$record->posisi}</span><br><span class='fi-color fi-color-primary fi-text-color-600 dark:fi-text-color-200 fi-badge fi-size-sm'>{$record->status->value}</span>")
                    ->searchable(),
                TextColumn::make('nama_lengkap')
                    ->weight(FontWeight::Bold)
                    ->visibleFrom('md')
                    ->searchable(),
                TextColumn::make('email')
                    ->visibleFrom('md')
                    ->searchable(),
                TextColumn::make('posisi')
                    ->visibleFrom('md')
                    ->alignCenter()
                    ->badge()
                    ->color(function ($record) {
                        // Custom hex palette (non-default Filament colors)
                        $colors = [
                            Color::hex('#8B5CF6'), // violet-500
                            Color::hex('#EC4899'), // pink-500
                            Color::hex('#14B8A6'), // teal-500
                            Color::hex('#F59E0B'), // amber-500
                            Color::hex('#06B6D4'), // cyan-500
                        ];

                        // Stable pick per record (deterministic by id)
                        $index = crc32((string) $record->id) % count($colors);
                        return $colors[$index];
                    })
                    ->searchable(),
                TextColumn::make('status')
                    ->visibleFrom('md')
                    ->badge(),
                TextColumn::make('tanggal_bergabung')
                    ->visibleFrom('md')
                    ->date()
                    ->sortable(),
                TextColumn::make('tanggal_expired')
                    ->visibleFrom('md')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make()->label(''),
                    EditAction::make()->label('')->color('gray'),
                    DeleteAction::make()->label('')->color('danger'),
                ])->buttonGroup()
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
