<?php

namespace App\Filament\Resources\Timesheets\RelationManagers;

use App\Enum\Timesheet\Location;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\View\Components\ButtonComponent;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

class IsiTimesheetRelationManager extends RelationManager
{
    protected static string $relationship = 'isi_timesheet';
    protected static ?string $relationshipTitle = 'Isi Timesheet';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                ToggleButtons::make('location')->options(Location::class)->inline(),
                DatePicker::make('tanggal')->label(__('Date'))
                    ->native(false),
                ToggleButtons::make('jam_bekerja')->options([8=>'Satu hari',4=>'Setengah hari']),
                TextInput::make('place'),
                TextInput::make('work_done')
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('karyawan.nama_lengkap')
            ->columns($this->getTableColumns())
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
//                AssociateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DissociateBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
    protected function getTableColumns(): array
    {
        return match ($this->activeTab) {
            'Kehadiran' => [
                TextColumn::make('location'),
                TextColumn::make('tanggal')->date('d'),
                TextColumn::make('jam_bekerja'),
                TextColumn::make('place'),
            ],
            'Aktifitas' => [
                TextColumn::make('tanggal')->date('d F Y'),
                TextColumn::make('day_worked')->state(fn ($record)=>match ($record->jam_bekerja){
                    8=>'1',
                    default=>'0'
                }),
                TextColumn::make('place'),
                TextColumn::make('work_done'),
            ],
            default => [],
        };
    }

    public function getTabs(): array
    {
        return [
            'Kehadiran'=>Tab::make(),
            'Aktifitas'=>Tab::make(),
        ];
    }
}
