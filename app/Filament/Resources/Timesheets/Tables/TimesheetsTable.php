<?php

namespace App\Filament\Resources\Timesheets\Tables;

use App\Enum\Cuti\StatusPengajuan;
use App\Enum\Timesheet\StatusPersetujuan;
use App\Models\Cuti;
use App\Models\Timesheet;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Support\Enums\TextSize;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
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
                TextColumn::make('status')
                    ->size(TextSize::Large)
                    ->badge(),
            ])->defaultGroup(\Filament\Tables\Grouping\Group::make('tanggal')
                ->groupQueryUsing(fn(Builder $q)=>$q->select(DB::raw('MONTHNAME(tanggal) as bulan'), DB::raw('YEAR(tanggal) as tahun'))
                    ->groupBy('bulan')
                    ->groupBy('tahun'))->date())
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('Approve')
                    ->color(fn($record)=>match ($record->status) {
                        default=>'gray',
                        StatusPersetujuan::Diteruskan=>'success',
                    })
                    ->disabled(fn($record)=>$record->status!==StatusPersetujuan::Diteruskan)
                    ->button()
                    ->authorize('approve')
                    ->form([
                        \Filament\Schemas\Components\Grid::make(2)->schema([
                            TextInput::make('approved_at')
                                ->label('Tempat Disetujui')
                                ->default('Pontianak')
                                ->required(),
                            DatePicker::make('approved_date')
                                ->native(false)
                                ->displayFormat('d F Y')
                                ->label('Tanggal Disetujui')
                                ->default(now())
                                ->required(),
                            Section::make('Tanda Tangan Direktur')->schema([
                                Radio::make('signature_method')
                                    ->live()
                                    ->options([
                                        'upload' => 'Upload PNG',
                                        'draw' => 'Gambar Tanda Tangan',
                                    ])
                                    ->default('upload')
                                    ->inline()
                                    ->required(),

                                // Upload PNG option
                                FileUpload::make('signature_png')
                                    ->label('File PNG tanda tangan')
                                    ->image()
                                    ->imageEditor(false)
                                    ->acceptedFileTypes(['image/png'])
                                    ->maxSize(1024) // KB
                                    ->disk('public')
                                    ->directory('signatures')
                                    ->visibility('public')
                                    ->storeFileNamesIn('signature_png_name')
                                    ->required(fn (callable $get) => $get('signature_method') === 'upload')
                                    ->visible(fn (callable $get) => $get('signature_method') === 'upload'),

//                                // Canvas untuk gambar signature
//                                View::make('signature-wrapper')
//                                    ->visible(fn (callable $get) => $get('signature_method') === 'draw'),
//
//                                // Hidden field untuk data URL signature
//                                TextInput::make('signature_drawn')
//                                    ->label('Data URL Signature')
//                                    ->extraInputAttributes(['wire:model.defer' => 'mountedTableActionData.signature_drawn'])
//                                    ->hidden()
//                                    ->dehydrated(true)
//                                    ->default('')
//                                    ->required(fn (callable $get) => $get('signature_method') === 'draw'),
                            ])->columnSpanFull(),
                        ])
                    ])
                    ->action(function (Timesheet $record, array $data) {
                        try {
                            $documentService = app(\App\Services\CutiDocumentServiceNew::class);

                            // Simpan signature direktur ke storage
                            $signaturePath = null;
                            $signatureMethod = $data['signature_method'] ?? null;

//                            \Log::info('Approve Action - Processing', [
//                                'signature_method' => $signatureMethod,
//                                'has_signature_png' => !empty($data['signature_png']),
//                                'has_signature_drawn' => !empty($data['signature_drawn']),
//                                'signature_png_value' => $data['signature_png'] ?? null,
//                                'signature_drawn_length' => isset($data['signature_drawn']) ? strlen($data['signature_drawn']) : 0,
//                            ]);

                            if ($signatureMethod === 'upload' && !empty($data['signature_png'])) {
                                $signaturePath = $data['signature_png'];
                                \Log::info('Using upload signature (direktur)', ['path' => $signaturePath]);
                            }

                            if ($signatureMethod === 'draw' && !empty($data['signature_drawn'])) {
                                $signaturePath = $data['signature_drawn'];
                                \Log::info('Using draw signature (direktur)', ['data_url_length' => strlen($signaturePath)]);
                            }

                            if ($signaturePath) {
                                $signatureFile = $documentService->saveSignature($signaturePath, 'direktur');
                                $record->update(['signature_direktur' => $signatureFile]);
                                \Log::info('Direktur signature saved to database', ['path' => $signatureFile]);
                            } else {
                                \Log::warning('No direktur signature path provided');
                            }

                            // Update status approval
                            $record->approve(Auth::user());

                            // Update dokumen existing dengan approval dan signature
//                            $documentService->updateDocumentWithApproval($record);

                        } catch (\Exception $e) {
                            \Filament\Notifications\Notification::make()
                                ->title('Error Update Dokumen')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();

                            \Log::error('Approve action failed', ['error' => $e->getMessage()]);
                            return null;
                        }
                    })
                    ->successNotificationTitle('Timesheet berhasil disetujui dan dokumen telah dibuat')
                    ->requiresConfirmation()
                    ->modalHeading('Approve Timesheet')
                    ->modalSubmitActionLabel('Konfirmasi'),
                Action::make('Reject')
                    ->requiresConfirmation()
                    ->disabled(fn($record)=>$record->status!==StatusPersetujuan::Diteruskan)
                    ->color(fn($record)=>match ($record->status) {
                        default=>'gray',
                        StatusPersetujuan::Diteruskan=>'danger',
                    })
                    ->action(function (Timesheet $record) {
                        // Hapus dokumen dan signature files (TANPA HAPUS RECORD)
//                        $service = app(\App\Services\CutiDocumentServiceNew::class);
//                        $service->cleanupAllDocuments($record, $record->file_path, $record->signature_karyawan, $record->signature_direktur, $record->lampiran);
                        $record->rejected(Auth::user());
                    })
                    ->successNotificationTitle('Timesheet berhasil ditolak')
                    ->modalDescription('Apakah Anda yakin ingin menolak pengajuan ini? Dokumen akan dihapus.')
                    ->button()
                    ->authorize('reject'),
                Action::make('Teruskan')
                    ->successNotificationTitle('Timesheet berhasil diteruskan ke direktur')
                    ->authorize('direct')
                    ->color('danger')
                    ->button()
                    ->disabled(fn($record)=>$record->status!==StatusPersetujuan::Dilihat)
                    ->action(fn(Timesheet $record)=>$record->directTo(Auth::user())),
                Action::make('Tandai Dilihat')
                    ->successNotificationTitle('Timesheet berhasil ditandai sebagai dilihat')
                    ->authorize('direct')
                    ->button()
                    ->disabled(fn($record)=>$record->status!==StatusPersetujuan::Dibuat)
                    ->action(fn(Timesheet $record)=>$record->reviewed(Auth::user())),
                ActionGroup::make([
//                    ViewAction::make()->label(''),
                    EditAction::make()->label('')->color('gray'),
                    DeleteAction::make()->label(''),
                ])->buttonGroup()
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->authorize('delete'),
                ]),
            ])->poll('10s');
    }
}
