<?php

namespace App\Filament\Resources\Master\Karyawans\Schemas;

use App\Enum\Master\StatusKerja;
use Filament\Actions\Action;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;
use Filament\Support\Icons\Heroicon;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

class KaryawanInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detail')
                    ->schema([
                        \Filament\Schemas\Components\Group::make([
                            Section::make()
                                ->secondary()
                                ->schema([
                                    TextEntry::make('nama_lengkap')
                                        ->BelowContent([
                                            \Filament\Schemas\Components\Group::make([
                                                TextEntry::make('posisi')
                                                    ->hiddenLabel()
                                                    ->size(TextSize::Large)
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
                                                    }),
                                                TextEntry::make('status')
                                                    ->hiddenLabel()
                                                    ->size(TextSize::Large)
                                                    ->badge(),
                                            ])
                                        ])
                                        ->weight(FontWeight::Bold)
                                        ->size(TextSize::Large)
                                        ->hiddenLabel(),
                                    \Filament\Schemas\Components\Group::make([
                                        TextEntry::make('email')
                                            ->alignEnd()
                                            ->icon(Heroicon::Envelope)
                                            ->hiddenLabel(),
                                        TextEntry::make('npwp')
                                            ->alignEnd()
                                            ->hiddenLabel(),
                                        TextEntry::make('unit_kerja')
                                            ->alignEnd()
                                            ->hiddenLabel(),
                                    ])->dense()

                                ])->columns(2),
                            Section::make()
                                ->secondary()
                                ->schema([
                                    TextEntry::make('tempat_lahir'),
                                    TextEntry::make('tanggal_lahir')
                                        ->date(),
                                    TextEntry::make('jenis_kelamin'),
                                    TextEntry::make('riwayat_pendidikan'),
                                    TextEntry::make('institusi_pendidikan'),
                                    TextEntry::make('english_skill')
                                        ->badge(),
                                    TextEntry::make('pengalaman_kerja')
                                        ->numeric(),
                                ])->columns(2),
                        ]),
                        \Filament\Schemas\Components\Group::make([
                            Section::make()
                                ->secondary()
                                ->schema([
                                    TextEntry::make('tanggal_bergabung')
                                        ->date(),
                                    TextEntry::make('tanggal_expired')
                                        ->visible(fn ($record):bool => !is_null($record->tanggal_expired))
                                        ->date(),
                                    TextEntry::make('masa_kerja')
                                        ->numeric(),
                                ])->columns(3),
                            Flex::make([
                                Section::make()
                                ->schema([
                                    Action::make('downloadCv')
                                        ->label('Download CV')
                                        ->icon('heroicon-o-arrow-down-tray')
                                        ->action(function ($record) {
                                            try {
                                                return response()->download(
                                                    storage_path('app/public/' . $record->cv)
                                                );
                                            } catch (FileNotFoundException $e) {
                                                return redirect(to: "$record->cv",status: '404 Not Found');
                                            }
                                        }),
                                    Action::make('downloadKtp')
                                        ->label('Download KTP')
                                        ->icon('heroicon-o-arrow-down-tray')
                                        ->action(function ($record) {
                                            try {
                                                return response()->download(
                                                    storage_path('app/public/' . $record->ktp)
                                                );
                                            } catch (FileNotFoundException $e) {
                                                return redirect(to: "$record->ktp",status: '404 Not Found');
                                            }

                                        }),
                                    Action::make('downloadKk')
                                        ->label('Download KK')
                                        ->icon('heroicon-o-arrow-down-tray')
                                        ->action(function ($record) {
                                            try {
                                                return response()->download(
                                                    storage_path('app/public/' . $record->kk)
                                                );
                                            } catch (FileNotFoundException $e) {
                                                return redirect(to: "$record->kk",status: '404 Not Found');
                                            }

                                        }),
                                ])->columns(3)
                            ])
                        ]),
                    ])->columns(2),
            ])->columns(1);
    }
}



