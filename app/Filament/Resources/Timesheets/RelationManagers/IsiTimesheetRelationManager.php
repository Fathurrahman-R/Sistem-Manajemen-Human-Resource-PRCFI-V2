<?php

namespace App\Filament\Resources\Timesheets\RelationManagers;

use App\Enum\Timesheet\Location;
use App\Models\Timesheet;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\Summarizers\Summarizer;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class IsiTimesheetRelationManager extends RelationManager
{
    protected static string $relationship = 'isi_timesheet';


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
            ->paginated(false)
            ->recordTitleAttribute('karyawan.nama_lengkap')
            ->columns($this->getTableColumns())
            ->striped()
            ->filters([
                SelectFilter::make('location')->options(Location::class)->native(false)
            ])
            ->filtersLayout(FiltersLayout::AboveContentCollapsible)
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
                    DeleteBulkAction::make(),
                ]),
            ])
            ->groups([
                Group::make('location')
                    ->collapsible(),
            ])
            ->defaultGroup('location');
    }
    protected function mapJamKeHari(int|float|null $jam): float
    {
        return match (true) {
            $jam >= 8 => 1,
            $jam === 4 => 0.5,
            default => 0,
        };
    }
    protected function getTableColumns(): array
    {
        return match ($this->activeTab) {
            'Kehadiran' => [
                TextColumn::make('tanggal')->label('Date')->date('d'),
                TextColumn::make('jam_bekerja')
                    ->summarize([
                        Sum::make()->label('Total Jam Bekerja'),
                    ]),
                TextColumn::make('day_worked')->label('day worked')
                    ->state(fn ($record) => $this->mapJamKeHari($record->jam_bekerja))
                    ->summarize(
                        Summarizer::make()
                            ->label('Total Hari Bekerja')
                            ->using(fn ($query) => $query->selectRaw(
                                'SUM(CASE WHEN jam_bekerja >= 8 THEN 1 WHEN jam_bekerja = 4 THEN 0.5 ELSE 0 END) as total'
                            )->value('total'))
                    ),
                TextColumn::make('place'),
            ],
            'Aktifitas' => [
                TextColumn::make('tanggal')->date('d F Y'),
                TextColumn::make('jam_bekerja')->label('day worked')
                    ->state(fn ($record) => $this->mapJamKeHari($record->jam_bekerja))
                    ->summarize(
                        Summarizer::make()
                            ->label('Total Hari Bekerja')
                            ->using(fn ($query) => $query->selectRaw(
                                'SUM(CASE WHEN jam_bekerja >= 8 THEN 1 WHEN jam_bekerja = 4 THEN 0.5 ELSE 0 END) as total'
                            )->value('total'))
                    ),
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
