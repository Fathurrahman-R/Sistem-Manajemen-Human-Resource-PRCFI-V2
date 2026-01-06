<?php

namespace App\Filament\Resources\Master\Karyawans\Schemas;

use App\Enum\Master\EnglishSkill;
use App\Enum\Master\RiwayatPendidikan;
use App\Enum\Master\StatusKerja;
use App\Filament\Resources\Master\Karyawans\RelationManagers\ProgramRelationManager;
use App\Filament\Tables\ProgramTabelResource;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\ModalTableSelect;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TableSelect;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\FusedGroup;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class KaryawanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data Diri')
                    ->schema([
                        Group::make()
                            ->schema([
                                TextInput::make('npwp')
                                    ->prefixIcon(Heroicon::Scale)
                                    ->label('NPWP')
                                    ->validationAttribute('NPWP')
                                    ->placeholder('012.345.678.9-876.543')
                                    ->required(),
                                TextInput::make('nama_lengkap')
                                    ->minLength(3)
                                    ->maxLength(100)
                                    ->prefixIcon(Heroicon::Identification)
                                    ->placeholder('John Doe')
                                    ->required(),
                                Radio::make('jenis_kelamin')
                                    ->options(['Laki-laki' => 'Laki-laki', 'Perempuan' => 'Perempuan'])
                                    ->required()->inline(),
                                TextInput::make('email')
                                    ->prefixIcon(Heroicon::Envelope)
                                    ->label('Alamat email')
                                    ->placeholder('myemail@example.com')
                                    ->maxLength(100)
                                    ->email()
                                    ->required(),
                                TextInput::make('unit_kerja')
                                    ->minLength(3)
                                    ->maxLength(100)
                                    ->prefixIcon(Heroicon::Map)
                                    ->placeholder('Pontianak')
                                    ->required(),
                                TextInput::make('posisi')
                                    ->minLength(2)
                                    ->maxLength(100)
                                    ->prefixIcon(Heroicon::ShieldCheck)
                                    ->placeholder('Direktur')
                                    ->required(),
                                FusedGroup::make([
                                    TextInput::make('tempat_lahir')
                                        ->minLength(3)
                                        ->maxLength(100)
                                        ->prefixIcon(Heroicon::MapPin)
                                        ->placeholder('Jakarta')
                                        ->required()->columnSpan(2),
                                    DatePicker::make('tanggal_lahir')
                                        ->prefixIcon(Heroicon::Calendar)
                                        ->native(false)
                                        ->closeOnDateSelection()
                                        ->minDate(now()->subYears(90)->startOfDay())
                                        ->maxDate(now()->subYears(15)->startOfDay())
                                        ->displayFormat('d F Y')
                                        ->default(now()->subYears(25)->startOfDay())
                                        ->placeholder(now()->subYears(25)->startOfDay()->format('d F Y'))
                                        ->required(),
                                ])->label('Tempat/Tanggal Lahir')->columns(3)->columnSpan(2),
                                TextInput::make('pengalaman_kerja')
                                    ->prefixIcon(Heroicon::ArrowTrendingUp)
                                    ->suffix('Tahun')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(90)
                                    ->default(0),
                                FusedGroup::make([
                                    TextInput::make('institusi_pendidikan')
                                        ->prefixIcon(Heroicon::AcademicCap)
                                        ->minLength(3)
                                        ->maxLength(100)
                                        ->placeholder('Universitas Tanjungpura')
                                        ->columnSpan(2),
                                    Select::make('riwayat_pendidikan')
                                        ->prefixIcon(Heroicon::BuildingLibrary)
                                        ->options(RiwayatPendidikan::class)->native(false),
                                ])
                                    ->label('Riwayat pendidikan')
                                    ->columns(3)->columnSpan(2),
                                TextInput::make('masa_kerja')
                                    ->prefixIcon(Heroicon::CalendarDateRange)
                                    ->suffix('Tahun')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(90)
                                    ->default(0),
                                FusedGroup::make([
                                    Select::make('status')
                                        ->native(false)
                                        ->default(StatusKerja::Kontrak)
                                        ->live()
                                        ->options(StatusKerja::class)
                                        ->required(),
                                    DatePicker::make('tanggal_bergabung')
                                        ->prefix('Bergabung')
                                        ->native(false)
                                        ->closeOnDateSelection()
                                        ->date()
                                        ->minDate(now()->subYears(90)->startOfDay())
                                        ->maxDate(now()->startOfDay())
                                        ->default(now()->startOfDay())
                                        ->displayFormat('d F Y')
                                        ->placeholder(now()->subYears(10)->format('d F Y'))
                                        ->required()->columnSpan(2),
                                    DatePicker::make('tanggal_expired')
                                        ->prefix('-')
                                        ->suffix('Expired')
                                        ->closeOnDateSelection()
                                        ->date()
                                        ->minDate(now()->subYears(90)->startOfDay())
                                        ->maxDate(now()->addYears(90)->startOfDay())
                                        ->default(now()->addYears(1)->startOfDay())
                                        ->displayFormat('d F Y')
                                        ->placeholder(now()->format('d F Y'))
                                        ->hidden(function (Get $get):bool{
                                            return $get('status') === StatusKerja::Tetap;
                                        })
                                        ->native(false)
                                        ->columnSpan(2),
                                ])
                                    ->columns(5)
                                    ->columnSpan(2)
                                    ->label('Status'),
                                ToggleButtons::make('english_skill')
                                    ->required()
//                                    ->default(EnglishSkill::Low)
                                    ->options(EnglishSkill::class)
                                    ->inline(),
                            ])->columns(3),
                        Section::make('Dokumen')
                            ->secondary()
                            ->inlineLabel()
                            ->schema([
                                FileUpload::make('cv')
                                    ->previewable()
                                    ->label('CV')
                                    ->disk('public')
                                    ->directory('cv')
                                    ->visibility('public'),
                                FileUpload::make('ktp')
                                    ->label('KTP')
                                    ->disk('public')
                                    ->directory('ktp')
                                    ->visibility('public'),
                                FileUpload::make('kk')
                                    ->label('KK')
                                    ->disk('public')
                                    ->directory('kk')
                                    ->visibility('public'),
                            ])->columnSpanFull(),
                    ])->columnSpan(2),
            ]);
    }
}
