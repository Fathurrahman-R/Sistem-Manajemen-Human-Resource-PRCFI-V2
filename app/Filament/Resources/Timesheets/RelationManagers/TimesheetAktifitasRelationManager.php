<?php

namespace App\Filament\Resources\Timesheets\RelationManagers;

use App\Enum\Timesheet\Location;
use App\Filament\Schemas\IsiTimesheetForm;
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
use Filament\Schemas\Schema;
use Filament\Tables\Columns\Summarizers\Summarizer;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;

class TimesheetAktifitasRelationManager extends RelationManager
{
    protected static string $relationship = 'isi_timesheet';

    protected static ?string $relationshipTitle = 'Timesheet Aktifitas';

    public function form(Schema $schema): Schema
    {
        return IsiTimesheetForm::configure($schema);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('location')
            ->defaultSort('tanggal', 'asc')
            ->persistSortInSession()
            ->striped()
            ->paginated(false)
            ->columns([
                // Kolom untuk aktifitas
                TextColumn::make('tanggal_aktifitas')
                    ->state(fn($record)=>$record->tanggal)
                    ->date('D, d F Y'),
                TextColumn::make('day_worked')
                    ->label('day worked')
                    ->state(fn ($record) => $this->mapJamKeHari($record->jam_bekerja))
                    ->summarize(
                        Summarizer::make()
                            ->label('Total Hari Bekerja')
                            ->using(fn ($query) => $query->selectRaw(
                                'SUM(CASE WHEN jam_bekerja >= 8 THEN 1 WHEN jam_bekerja = 4 THEN 0.5 ELSE 0 END) as total'
                            )->value('total'))
                    ),
                TextColumn::make('place')
                    ->default(function ($record){
                        return !is_null($record->place)?:'-';
                    }),
                TextColumn::make('work_done'),
            ])
            ->filters([
                SelectFilter::make('location')->options(Location::class)->native(false)
            ])
            ->headerActions([
                CreateAction::make(),
//                AssociateAction::make(),
            ])
            ->recordActions([
                EditAction::make()
                    ->disabled(function ($record) {
                        return $record->location==Location::Leave || $record->location==Location::SickLeave;
                    }),
//                DissociateAction::make(),
                DeleteAction::make()
                    ->disabled(function ($record) {
                        return $record->location==Location::Leave || $record->location==Location::SickLeave;
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
//                    DissociateBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ])
            ->groups([
                Group::make('location')
                    ->titlePrefixedWithLabel(false)
                    ->collapsible(),
            ])
            ->groupingSettingsHidden()
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
}
