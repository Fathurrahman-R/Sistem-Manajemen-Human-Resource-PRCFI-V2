<?php

namespace App\Filament\Resources\Timesheets\RelationManagers;

use App\Enum\Timesheet\Location;
use App\Filament\Schemas\IsiTimesheetForm;
use Filament\Actions\ActionGroup;
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
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;

class TimesheetKehadiranRelationManager extends RelationManager
{
    protected static string $relationship = 'isi_timesheet';

    protected static ?string $relationshipTitle = 'Timesheet Kehadiran';

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
                // Kolom untuk kehadiran
//                TextColumn::make('location'),
                TextColumn::make('tanggal')
                    ->label('Date')
                    ->date('D, d F Y'),
                TextColumn::make('jam_bekerja')
                    ->state(fn($record)=>$record->jam_bekerja.' jam')
                    ->summarize([
                        Sum::make()
                            ->label('Total Jam Bekerja'),
                    ]),
            ])
            ->filters([
                SelectFilter::make('location')->options(Location::class)->native(false)
            ])
            ->headerActions([
                CreateAction::make(),
//                AssociateAction::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make()
                        ->disabled(function ($record) {
                            return $record->location==Location::Leave || $record->location==Location::SickLeave;
                        })->label('')->color('gray'),
                    DeleteAction::make()
                        ->disabled(function ($record) {
                            return $record->location==Location::Leave || $record->location==Location::SickLeave;
                        })->label('')->color('danger'),
                ])->buttonGroup()
            ])
            ->toolbarActions([
                BulkActionGroup::make([
//                    DissociateBulkAction::make(),
                    DeleteBulkAction::make()
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
