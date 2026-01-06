<?php

namespace App\Filament\Resources\Master\Karyawans\Tables;

use App\Enum\Colors;
use App\Enum\Master\StatusKerja;
use App\Models\User;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class KaryawansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('avatar')
                    ->label('Foto')
                    ->imageSize(50)
                    ->disk('public')
                    ->rounded()
                    ->state(function ($record){
                        $avatarUrl = User::where('karyawan_id', $record->id)
                            ->value('avatar_url');
//                        dd($avatarUrl);
                        return $avatarUrl;
                    }),
                TextColumn::make('nama_lengkap')
                    ->weight(FontWeight::Bold)
                    ->searchable(),
                TextColumn::make('email')
                    ->visibleFrom('md')
                    ->searchable(),
                TextColumn::make('posisi')
                    ->alignCenter()
                    ->badge()
                    ->size(TextSize::Large)
                    ->color(function () {
                        $color = Arr::random(Colors::cases());
                        return $color->getColor();
                    })
                    ->visibleFrom('md')
                    ->searchable(),
                TextColumn::make('status')
                    ->visibleFrom('md')
                    ->badge()
                    ->size(TextSize::Large),
                TextColumn::make('tanggal_bergabung')
                    ->visibleFrom('md')
                    ->date('d F Y')
                    ->sortable(),
                TextColumn::make('tanggal_expired')
                    ->visibleFrom('md')
                    ->date('d F Y')
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
