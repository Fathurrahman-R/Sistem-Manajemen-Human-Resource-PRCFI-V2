<?php

namespace App\Filament\Resources\Cutis\Schemas;

use App\Enum\Cuti\StatusPengajuan;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\FusedGroup;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Livewire;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;

class CutiForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make([
                    TextInput::make('karyawan_id')
                        ->default(User::getKaryawanId(Auth::user()))
                        ->hidden()
                        ->required(),
                    Grid::make(2)->schema([
                        TextInput::make('tempat_dibuat')
                            ->readOnly(function($record,$operation){
                                if($operation!=='create'){
                                    if ($record->status!==StatusPengajuan::Diajukan->value){
                                        return true;
                                    }
                                    return false;
                                }else{
                                    return false;
                                }
                            })
                            ->placeholder('Pontianak')
                            ->prefixIcon(Heroicon::MapPin)
                            ->required(),
                        DatePicker::make('tanggal_dibuat')
                            ->readOnly(function($record,$operation){
                                if($operation!=='create'){
                                    if ($record->status!==StatusPengajuan::Diajukan->value){
                                        return true;
                                    }
                                    return false;
                                }else{
                                    return false;
                                }
                            })
                            ->displayFormat('d F Y')
                            ->placeholder(date('d F Y'))
                            ->prefixIcon(Heroicon::Calendar)
                            ->native(false)
                            ->required(),
                    ]),
                    FusedGroup::make([
                        DatePicker::make('tanggal_mulai')
                            ->readOnly(function($record,$operation){
                                if($operation!=='create'){
                                    if ($record->status!==StatusPengajuan::Diajukan->value){
                                        return true;
                                    }
                                    return false;
                                }else{
                                    return false;
                                }
                            })
                            ->prefixIcon(Heroicon::CalendarDateRange)
                            ->suffix('Sampai dengan')
                            ->displayFormat('d F Y')
                            ->placeholder(date('d F Y'))
                            ->native(false)
                            ->required(),
                        DatePicker::make('tanggal_selesai')
                            ->readOnly(function($record,$operation){
                                if($operation!=='create'){
                                    if ($record->status!==StatusPengajuan::Diajukan->value){
                                        return true;
                                    }
                                    return false;
                                }else{
                                    return false;
                                }
                            })
                            ->prefixIcon(Heroicon::CalendarDateRange)
                            ->displayFormat('d F Y')
                            ->placeholder(date('d F Y'))
                            ->native(false)
                            ->required(),
                    ])
                        ->label('Lama cuti'),
                    Textarea::make('keterangan')
                        ->readOnly(function($record,$operation){
                            if($operation!=='create'){
                                if ($record->status!==StatusPengajuan::Diajukan->value){
                                    return true;
                                }
                                return false;
                            }else{
                                return false;
                            }
                        })
                        ->required()
                        ->columnSpanFull(),
                ]),
                Section::make([
                    FileUpload::make('lampiran')
                        ->label('Lampiran (Opsional)')
                        ->helperText('Upload lampiran seperti surat keterangan dokter, dll.')
                        ->directory('lampiran-cuti')
                        ->visibility('private')
                        ->multiple()
                        ->maxFiles(5)
                        ->maxSize(5120) // 5MB
                        ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png'])
                        ->downloadable()
                        ->openable()
                        ->previewable()
                        ->getUploadedFileNameForStorageUsing(function ($file, $get) {
                            $karyawan = Auth::user()->karyawan;
                            $namaKaryawan = $karyawan ? str_replace(' ', '_', $karyawan->nama_lengkap) : 'Unknown';
                            $keterangan = $get('keterangan') ? str_replace(' ', '_', substr($get('keterangan'), 0, 30)) : 'Cuti';
                            $tanggal = now()->format('Ymd_His');
                            $extension = $file->getClientOriginalExtension();

                            return "{$namaKaryawan}_{$keterangan}_{$tanggal}.{$extension}";
                        })
                        ->disabled(function($record,$operation){
                            if($operation!=='create'){
                                if ($record->status!==StatusPengajuan::Diajukan->value){
                                    return true;
                                }
                                return false;
                            }else{
                                return false;
                            }
                        })
                        ->columnSpanFull(),

                    Section::make('Tanda Tangan Pemohon')
                        ->description('Sertakan tanda tangan Anda pada surat pengajuan')
                        ->schema([
                            Radio::make('signature_method')
                                ->label('Metode Tanda Tangan')
                                ->live()
                                ->options([
                                    'upload' => 'Upload File PNG',
                                    'draw' => 'Gambar Tanda Tangan',
                                ])
                                ->default('upload')
                                ->inline()
                                ->required()
                                ->disabled(function($record,$operation){
                                    if($operation!=='create'){
                                        if ($record->status!==StatusPengajuan::Diajukan->value){
                                            return true;
                                        }
                                        return false;
                                    }else{
                                        return false;
                                    }
                                }),

                            FileUpload::make('signature_png')
                                ->label('File PNG Tanda Tangan')
                                ->image()
                                ->imageEditor(false)
                                ->acceptedFileTypes(['image/png'])
                                ->maxSize(1024)
                                ->disk('public')
                                ->directory('signatures')
                                ->visibility('public')
                                ->storeFileNamesIn('signature_png_name')
                                ->required(fn (callable $get) => $get('signature_method') === 'upload')
                                ->visible(fn (callable $get) => $get('signature_method') === 'upload')
                                ->disabled(function($record,$operation){
                                    if($operation!=='create'){
                                        if ($record->status!==StatusPengajuan::Diajukan->value){
                                            return true;
                                        }
                                        return false;
                                    }else{
                                        return false;
                                    }
                                }),

//                            View::make('signature-wrapper')
//                                ->visible(fn (callable $get) => $get('signature_method') === 'draw'),
                        ])
                        ->collapsible()
                        ->columnSpanFull(),
                ])
            ])->columns(2);
    }
}
