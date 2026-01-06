<?php

namespace App\Filament\Resources\Timesheets\RelationManagers;

use App\Enum\Cuti\StatusPengajuan;
use App\Enum\Timesheet\Location;
use App\Models\Cuti;
use App\Models\IsiTimesheet;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\FusedGroup;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class CutiRelationManager extends RelationManager
{
    protected static string $relationship = 'cuti';

    public function form(Schema $schema): Schema
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
                                    if ($record->status!==StatusPengajuan::Diajukan){
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
                                    if ($record->status!==StatusPengajuan::Diajukan){
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
                                    if ($record->status!==StatusPengajuan::Diajukan){
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
                                    if ($record->status!==StatusPengajuan::Diajukan){
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
                                if ($record->status!==StatusPengajuan::Diajukan){
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
                        ->disk('public')
                        ->visibility('public')
                        ->multiple()
                        ->maxFiles(5)
                        ->maxSize(5120) // 5MB
                        ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png'])
                        ->downloadable()
                        ->openable()
                        ->previewable()
//                        ->getUploadedFileNameForStorageUsing(function ($file, $get) {
//                            $karyawan = Auth::user()->karyawan;
//                            $namaKaryawan = $karyawan ? str_replace(' ', '_', $karyawan->nama_lengkap) : 'Unknown';
//                            $keterangan = $get('keterangan') ? str_replace(' ', '_', substr($get('keterangan'), 0, 30)) : 'Cuti';
//                            $tanggal = now()->format('Ymd_His');
//                            $extension = $file->getClientOriginalExtension();
//
//                            return "{$namaKaryawan}_{$keterangan}_{$tanggal}.{$extension}";
//                        })
                        ->disabled(function($record,$operation){
                            if($operation!=='create'){
                                if ($record->status!==StatusPengajuan::Diajukan){
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
                                        if ($record->status!==StatusPengajuan::Diajukan){
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
                                        if ($record->status!==StatusPengajuan::Diajukan){
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

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('keterangan')
            ->poll('10s')
            ->columns([
                Split::make([
                    Stack::make([
                        TextColumn::make('keterangan')
                            ->searchable()
                            ->description('Keterangan','above')
                            ->size(TextSize::Large)
                            ->weight(FontWeight::Bold),
                        TextColumn::make('tempat_dibuat')
                            ->icon(Heroicon::MapPin)
                            ->searchable(),
                        TextColumn::make('tanggal_dibuat')
                            ->icon(Heroicon::Calendar)
                            ->sortable()
                            ->date('d F Y'),
                    ]),
                    \Filament\Tables\Columns\Layout\Grid::make(2)->schema([
                        TextColumn::make('tanggal_mulai')
                            ->alignCenter()
                            ->description('Mulai dari','above')
                            ->date('d M Y'),
                        TextColumn::make('tanggal_selesai')
                            ->alignCenter()
                            ->description('Sampai dengan','above')
                            ->date('d M Y'),
                    ])->visibleFrom('md'),
                    Stack::make([
                        TextColumn::make('jumlah_lampiran')
                            ->alignEnd()
                            ->label('Lampiran')
                            ->badge()
                            ->color('info')
                            ->icon('heroicon-o-paper-clip')
                            ->formatStateUsing(fn($state) => $state . ' file'),
                        TextColumn::make('status')
                            ->alignEnd()
                            ->size(TextSize::Large)
                            ->badge(),
                    ])
                ]),
            ])
            ->striped()
            ->groups([
                Group::make('karyawan.nama_lengkap')
                    ->collapsible()
                    ->titlePrefixedWithLabel(false)
            ])
            ->groupingSettingsHidden()
            ->defaultGroup('karyawan.nama_lengkap')
            ->searchable()
            ->filters([
                SelectFilter::make('status')
                    ->native(false)
                    ->options(StatusPengajuan::class)
                    ->attribute('status'),
            ])
            ->filtersLayout(FiltersLayout::Dropdown)
            ->headerActions([
                AttachAction::make()
//                    ->recordSelectOptionsQuery(fn (Builder $query) => $query->where('karyawan_id',Auth::user()->karyawan_id)->where('status', StatusPengajuan::Disetujui))
//                    ->preloadRecordSelect()
                    ->recordSelect(function ($select) {
                        return $select
//                            ->preload()
//                            ->searchable()
                            // labels for search results
                            ->native(false)
                            ->options(function (): array {
                                return Cuti::query()
                                    ->where('karyawan_id', Auth::user()->karyawan_id)
                                    ->where('status', StatusPengajuan::Disetujui)
//                                    ->when(strlen($search), fn ($q) =>
//                                    $q->where('keterangan', 'like', "%{$search}%")
//                                    )
                                    ->limit(50)
                                    ->get()
                                    ->mapWithKeys(function ($record) {
                                        $tgl = optional($record->tanggal_mulai)?->format('d F Y');
                                        $ket = trim((string) $record->keterangan);
                                        return [$record->id => "{$tgl} - {$ket}"];
                                    })
                                    ->toArray();
                            })
                            ->getOptionLabelUsing(function ($value): ?string {
                                $record = Cuti::query()
                                    ->where('karyawan_id', Auth::user()->karyawan_id)
                                    ->where('status', StatusPengajuan::Disetujui)
                                    ->find($value);

                                if (! $record) {
                                    return null;
                                }

                                $tgl = optional($record->tanggal_mulai)?->format('d F Y');
                                $ket = trim((string) $record->keterangan);
                                return "{$tgl} - {$ket}";
                            });
                    })
                    ->after(function (array $data): void {
                        // 1) Resolve owner timesheet and selected cuti
                        $timesheet = $this->getOwnerRecord(); // App\Models\Timesheet
                        $cutiId = $data['recordId'] ?? null;
                        if (! $cutiId) {
                            return;
                        }

                        $cuti = Cuti::query()
                            ->where('karyawan_id', auth()->user()->karyawan_id)
                            ->where('status', StatusPengajuan::Disetujui)
                            ->find($cutiId);

                        if (! $cuti) {
                            return;
                        }

                        // 2) Build date range (inclusive)
                        $period = CarbonPeriod::create(
                            Carbon::parse($cuti->tanggal_mulai)->startOfDay(),
                            Carbon::parse($cuti->tanggal_selesai)->startOfDay()
                        );

                        // Optional: restrict to the month of the owner timesheet
                        $monthStart = Carbon::parse($timesheet->tanggal)->startOfMonth();
                        $monthEnd   = Carbon::parse($timesheet->tanggal)->endOfMonth();

                        // 3) Enum location for leave (ensure this case exists in your Location enum)
                        // Replace with your actual enum case name/value.
                        $leaveLocation = Location::Leave;

                        // 4) Create/ensure rows per day
                        foreach ($period as $day) {
                            if ($day->lt($monthStart) || $day->gt($monthEnd)) {
                                continue;
                            }

                            IsiTimesheet::query()->updateOrCreate(
                                [
                                    'timesheet_id' => $timesheet->id,
                                    'tanggal'      => $day->toDateString(),
                                ],
                                [
                                    'jam_bekerja' => 1,
                                    'location'    => $leaveLocation,
                                    'place'       => 'Cuti',
                                    'work_done'   => 'Cuti: ' . (string) $cuti->keterangan,
                                ]
                            );
                        }
                    }),
            ])
            ->recordActions([
                DetachAction::make()
                    ->after(function (Cuti $record): void {
                        $timesheet = $this->getOwnerRecord();

                        $period = CarbonPeriod::create(
                            Carbon::parse($record->tanggal_mulai)->startOfDay(),
                            Carbon::parse($record->tanggal_selesai)->startOfDay()
                        );

                        $monthStart = Carbon::parse($timesheet->tanggal)->startOfMonth();
                        $monthEnd   = Carbon::parse($timesheet->tanggal)->endOfMonth();

                        foreach ($period as $day) {
                            if ($day->lt($monthStart) || $day->gt($monthEnd)) continue;

                            $dateStr = $day->toDateString();

                            // remove leave entries created by attach
                            IsiTimesheet::query()
                                ->where('timesheet_id', $timesheet->id)
                                ->whereDate('tanggal', $dateStr)
                                ->where('location', Location::Leave)
                                ->where('place', 'Cuti')
                                ->delete();

                            // restore weekend rows if day is weekend
                            if (in_array($day->dayOfWeekIso, [6, 7], true)) {
                                IsiTimesheet::query()->updateOrCreate(
                                    [
                                        'timesheet_id' => $timesheet->id,
                                        'tanggal'      => $dateStr,
                                    ],
                                    [
                                        'jam_bekerja' => 1,
                                        'location'    => Location::Weekends,
                                        'place'       => '',
                                        'work_done'   => Location::Weekends,
                                    ]
                                );
                            }
                        }
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DetachBulkAction::make(),
                ]),
            ]);
    }

}
