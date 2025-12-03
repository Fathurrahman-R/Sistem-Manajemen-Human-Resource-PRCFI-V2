<?php

namespace App\Filament\Resources\Master\Karyawans\Schemas;

use App\Enum\Master\EnglishSkill;
use App\Enum\Master\RiwayatPendidikan;
use App\Enum\Master\StatusKerja;
use App\Filament\Resources\Master\Karyawans\RelationManagers\ProgramRelationManager;
use App\Filament\Tables\ProgramTabelResource;
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
                                TextInput::make('nama_lengkap')
                                    ->prefixIcon(Heroicon::Identification)
                                    ->placeholder('John Doe')
                                    ->required()->columnSpan(2),
                                Radio::make('jenis_kelamin')
                                    ->options(['Laki-laki' => 'Laki-laki', 'Perempuan' => 'Perempuan'])
                                    ->required()->inline(),
                                TextInput::make('npwp')
                                    ->prefixIcon(Heroicon::Scale)
                                    ->label('NPWP')
                                    ->validationAttribute('NPWP')
                                    ->placeholder('012.345.678.9-876.543')
                                    ->required(),
                                TextInput::make('email')
                                    ->prefixIcon(Heroicon::Envelope)
                                    ->label('Alamat email')
                                    ->placeholder('myemail@example.com')
                                    ->email()
                                    ->required(),
                                TextInput::make('posisi')
                                    ->prefixIcon(Heroicon::ShieldCheck)
                                    ->placeholder('Direktur')
                                    ->required(),
                                FusedGroup::make([
                                    TextInput::make('tempat_lahir')
                                        ->prefixIcon(Heroicon::MapPin)
                                        ->placeholder('Jakarta')
                                        ->required()->columnSpan(2),
                                    DatePicker::make('tanggal_lahir')
                                        ->prefixIcon(Heroicon::Calendar)
                                        ->native(false)
                                        ->closeOnDateSelection()
                                        ->displayFormat('d F Y')
                                        ->placeholder(date('M d, Y'))
                                        ->required(),
                                ])->label('Tempat/Tanggal Lahir')->columns(3)->columnSpan(2),
                                TextInput::make('pengalaman_kerja')
                                    ->prefixIcon(Heroicon::ArrowTrendingUp)
                                    ->suffix('Tahun')
                                    ->numeric()
                                    ->default(0),
                                FusedGroup::make([
                                    TextInput::make('institusi_pendidikan')
                                        ->prefixIcon(Heroicon::AcademicCap)
                                        ->placeholder('Universitas Tanjungpura')
                                        ->columnSpan(2),
                                    Select::make('riwayat_pendidikan')
                                        ->prefixIcon(Heroicon::BuildingLibrary)
                                        ->options([
                                            RiwayatPendidikan::SD->name => RiwayatPendidikan::SD->value,
                                            RiwayatPendidikan::SMP->name => RiwayatPendidikan::SMP->value,
                                            RiwayatPendidikan::SMA->name => RiwayatPendidikan::SMA->value,
                                            RiwayatPendidikan::D1->name => RiwayatPendidikan::D1->value,
                                            RiwayatPendidikan::D2->name => RiwayatPendidikan::D2->value,
                                            RiwayatPendidikan::D3->name => RiwayatPendidikan::D3->value,
                                            RiwayatPendidikan::D4->name => RiwayatPendidikan::D4->value,
                                            RiwayatPendidikan::S1->name => RiwayatPendidikan::S1->value,
                                            RiwayatPendidikan::S2->name => RiwayatPendidikan::S2->value,
                                            RiwayatPendidikan::S3->name => RiwayatPendidikan::S3->value,
                                        ])->native(false),
                                ])
                                    ->label('Riwayat pendidikan')
                                    ->columns(3)->columnSpan(2),
                                TextInput::make('masa_kerja')
                                    ->prefixIcon(Heroicon::CalendarDateRange)
                                    ->suffix('Tahun')
                                    ->numeric()
                                    ->default(0),
                                FusedGroup::make([
                                    Select::make('status')
                                        ->native(false)
                                        ->default(StatusKerja::Kontrak->value)
                                        ->live()
                                        ->options([
                                            StatusKerja::Kontrak->name => StatusKerja::Kontrak->value,
                                            StatusKerja::Tetap->name => StatusKerja::Tetap->value,
                                            StatusKerja::Resign->name => StatusKerja::Resign->value,
                                        ])
                                        ->required(),
                                    DatePicker::make('tanggal_bergabung')
                                        ->prefix('Bergabung')
                                        ->native(false)
                                        ->closeOnDateSelection()
                                        ->date()
                                        ->displayFormat('d F Y')
                                        ->placeholder(date('d F Y'))
                                        ->required()->columnSpan(2),
                                    DatePicker::make('tanggal_expired')
                                        ->prefix('-')
                                        ->suffix('Expired')
                                        ->closeOnDateSelection()
                                        ->date()
                                        ->displayFormat('d F Y')
                                        ->placeholder(date('d F Y'))
                                        ->hidden(function (Get $get):bool{
                                            return $get('status') === StatusKerja::Tetap->value;
                                        })->native(false)->columnSpan(2),
                                ])->columns(5)->columnSpan(2)->label('Status'),
                                ToggleButtons::make('english_skill')
                                    ->required()
                                    ->default(EnglishSkill::Low->value)
                                    ->options([
                                        EnglishSkill::Low->name => EnglishSkill::Low->value,
                                        EnglishSkill::Medium->name => EnglishSkill::Medium->value,
                                        EnglishSkill::High->name => EnglishSkill::High->value,
                                    ])
                                    ->colors([
                                        EnglishSkill::High->value => 'success',
                                        EnglishSkill::Medium->value => 'warning',
                                        EnglishSkill::Low->value => 'danger',
                                    ])->inline(),
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
