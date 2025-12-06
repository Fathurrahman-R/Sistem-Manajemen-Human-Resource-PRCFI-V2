<?php

namespace App\Filament\Resources\Cutis\Tables;

use App\Enum\Cuti\StatusPengajuan;
use App\Models\Cuti;
use App\Permissions\Permission;
use App\Services\CutiDocumentServiceNew;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\View;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;
use Filament\Tables\Columns\Layout\Grid;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CutisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->poll('10s')
            ->columns([
                Split::make([
                    Stack::make([
                        TextColumn::make('keterangan')
                            ->description('Keterangan','above')
                            ->size(TextSize::Large)
                            ->weight(FontWeight::Bold),
                        TextColumn::make('tempat_dibuat')
                            ->searchable(),
                        TextColumn::make('tanggal_dibuat')
                            ->date('d F Y'),
                    ]),
                    Grid::make(2)->schema([
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
            ->defaultGroup('karyawan.nama_lengkap')
            ->searchable()
            ->filters([
                SelectFilter::make('status')
                    ->native(false)
                    ->options(StatusPengajuan::class)
                    ->attribute('status'),
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->recordActions([
                // Aksi approve direktur
                Action::make('Approve')
                    ->color(fn($record)=>match ($record->status) {
                        default=>'gray',
                        StatusPengajuan::MenungguDirektur->value=>'primary',
                    })
                    ->disabled(fn($record)=>$record->status!==StatusPengajuan::MenungguDirektur)
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
                    ->action(function (Cuti $record, array $data) {
                        try {
                            $documentService = app(\App\Services\CutiDocumentServiceNew::class);

                            // Simpan signature direktur ke storage
                            $signaturePath = null;
                            $signatureMethod = $data['signature_method'] ?? null;

                            \Log::info('Approve Action - Processing', [
                                'signature_method' => $signatureMethod,
                                'has_signature_png' => !empty($data['signature_png']),
                                'has_signature_drawn' => !empty($data['signature_drawn']),
                                'signature_png_value' => $data['signature_png'] ?? null,
                                'signature_drawn_length' => isset($data['signature_drawn']) ? strlen($data['signature_drawn']) : 0,
                            ]);

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
                            $record->approve(
                                $data['approved_at'],
                                $data['approved_date'],
                                Auth::user()
                            );

                            // Update dokumen existing dengan approval dan signature
                            $documentService->updateDocumentWithApproval($record);

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
                    ->successNotificationTitle('Pengajuan cuti berhasil disetujui dan dokumen telah dibuat')
                    ->requiresConfirmation()
                    ->modalHeading('Approve Pengajuan Cuti')
                    ->modalSubmitActionLabel('Konfirmasi'),
                Action::make('Reject')
                    ->requiresConfirmation()
                    ->disabled(fn($record)=>$record->status!==StatusPengajuan::MenungguDirektur)
                    ->color(fn($record)=>match ($record->status) {
                        default=>'gray',
                        StatusPengajuan::MenungguDirektur=>'danger',
                    })
                    ->action(function (Cuti $record) {
                        // Hapus dokumen dan signature files (TANPA HAPUS RECORD)
                        $service = app(\App\Services\CutiDocumentServiceNew::class);
                        $service->cleanupAllDocuments($record);
                        $record->reject(Auth::user());
                    })
                    ->successNotificationTitle('Pengajuan cuti berhasil ditolak dan dokumen dihapus')
                    ->modalDescription('Apakah Anda yakin ingin menolak pengajuan ini? Dokumen akan dihapus.')
                    ->button()
                    ->authorize('reject'),
                Action::make('Teruskan')
                    ->color(fn($record)=>match ($record->status) {
                        default=>'gray',
                        StatusPengajuan::MenungguHR=>'danger',
                    })
                    ->requiresConfirmation()
                    ->successNotificationTitle('Pengajuan cuti berhasil diteruskan ke direktur')
                    ->modalDescription('Apakah Anda yakin ingin melakukan ini? Aksi tidak bisa dibatalkan setelah menekan tombol konfirmasi')
                    ->disabled(fn($record)=>$record->status!==StatusPengajuan::MenungguHR)
                    ->action(fn(Cuti $record)=>$record->directTo(Auth::user()))
                    ->button()
                    ->authorize('direct'),
                Action::make('Diterima')
                    ->requiresConfirmation()
                    ->successNotificationTitle('Pengajuan cuti berhasil ditandai sebagai diterima')
                    ->modalDescription('Apakah Anda yakin ingin melakukan ini? Aksi tidak bisa dibatalkan setelah menekan tombol konfirmasi')
                    ->disabled(fn($record)=>$record->status!==StatusPengajuan::Diajukan)
                    ->color(fn($record)=>match ($record->status) {
                        default=>'gray',
                        StatusPengajuan::Diajukan=>'primary',
                    })
                    ->action(fn(Cuti $record)=>$record->rechieved(Auth::user()))
                    ->button()
                    ->authorize('direct'),

                EditAction::make()
                    ->button()
                    ->disabled(fn($record)=>$record->status!==StatusPengajuan::Diajukan),
//                    ->hidden(fn($record)=>$record->status!==StatusPengajuan::Diajukan),
                DeleteAction::make()
                    ->after(function(Cuti $record){
                        $service = app(\App\Services\CutiDocumentServiceNew::class);
                        $service->cleanupAllDocuments($record);
                    })
                    ->button()
                    ->disabled(fn($record)=>$record->status!==StatusPengajuan::Diajukan),
//                    ->hidden(fn($record)=>$record->status!==StatusPengajuan::Diajukan),
                // Lihat lampiran

                ActionGroup::make([
                    Action::make('Lampiran')
                        ->icon('heroicon-o-paper-clip')
                        ->color('primary')
                        ->disabled(fn($record) => !$record->hasLampiran())
                        ->authorize('view')
                        ->modalHeading('Lampiran Pengajuan Cuti')
                        ->modalContent(fn ($record): \Illuminate\Contracts\View\View => view(
                            'filament.pages.lampiran-modal-content',
                            ['record' => $record],
                        ))
                        ->modalSubmitAction(false)
                        ->modalCancelActionLabel('Tutup'),
                    // Aksi download surat cuti (hanya untuk yang sudah disetujui dan belum expired)
                    Action::make('Download Surat')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->color('success')
                        ->visible(fn($record) => $record->status === StatusPengajuan::Disetujui && $record->file_path)
                        ->disabled(fn($record) => $record->tanggal_selesai < now())
                        ->tooltip(fn($record) => $record->tanggal_selesai < now()
                            ? 'Dokumen tidak dapat didownload karena sudah melewati batas waktu cuti'
                            : 'Download surat cuti yang telah disetujui')
                        ->authorize('view')
                        ->action(function (Cuti $record) {
                            try {
                                if (!$record->file_path || !Storage::disk('public')->exists($record->file_path)) {
                                    \Filament\Notifications\Notification::make()
                                        ->title('File Tidak Ditemukan')
                                        ->body('Dokumen tidak ditemukan di sistem. Silakan hubungi administrator.')
                                        ->warning()
                                        ->send();
                                    return null;
                                }

                                $filePath = Storage::disk('public')->path($record->file_path);
                                $filename = basename($record->file_path);

                                return response()->download($filePath, $filename, [
                                    'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                ]);
                            } catch (\Exception $e) {
                                \Filament\Notifications\Notification::make()
                                    ->title('Error Download Dokumen')
                                    ->body($e->getMessage())
                                    ->danger()
                                    ->send();

                                return null;
                            }
                        }),
                    // Aksi preview dokumen untuk Admin & Direktur
                    Action::make('Lihat Dokumen')
                        ->icon('heroicon-o-document-text')
                        ->color('gray')
                        ->visible(fn($record) =>
                            $record->file_path
                            && Auth::user()->hasAnyRole([\App\Enum\Role::ADMIN, \App\Enum\Role::DIREKTUR])
                        )
                        ->authorize('view')
                        ->action(function (Cuti $record) {
                            try {
                                if (!$record->file_path || !Storage::disk('public')->exists($record->file_path)) {
                                    \Filament\Notifications\Notification::make()
                                        ->title('File Tidak Ditemukan')
                                        ->body('Dokumen tidak ditemukan di sistem.')
                                        ->warning()
                                        ->send();
                                    return null;
                                }

                                $filePath = Storage::disk('public')->path($record->file_path);
                                $filename = basename($record->file_path);

                                return response()->download($filePath, $filename, [
                                    'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                ]);
                            } catch (\Exception $e) {
                                \Filament\Notifications\Notification::make()
                                    ->title('Error')
                                    ->body($e->getMessage())
                                    ->danger()
                                    ->send();

                                return null;
                            }
                        }),
                ])->dropdownOffset(0)->dropdownPlacement('bottom-end'),
            ],position: RecordActionsPosition::AfterColumns)
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->visible(fn() =>Auth::user()->hasAnyDirectPermission([\App\Enum\Permission::DIRECT_MANAGE_CUTI, \App\Enum\Permission::REJECT_MANAGE_CUTI,\App\Enum\Permission::APPROVE_MANAGE_CUTI]))
                        ->after(function ($record) {
                            // Hapus semua files terkait
                            $service = app(CutiDocumentServiceNew::class);
                            $service->cleanupAllDocuments($record);
                        })
                ]),
            ])
            ->checkIfRecordIsSelectableUsing(function($record){
                if($record){
                    if ($record->status!==StatusPengajuan::Diajukan)
                    {
                        return false;
                    }
                    return true;
                };
                return true;
            });
    }
}
