<?php

namespace App\Filament\Resources\Timesheets\RelationManagers;

use App\Enum\Colors;
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
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\Summarizers\Count;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;

class TimesheetKehadiranRelationManager extends RelationManager
{
    protected static string $relationship = 'isi_timesheet';

    protected static ?string $relationshipTitle = 'Kehadiran';

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
                TextColumn::make('location')
                    ->icon(Heroicon::MapPin)
                    ->iconColor(Colors::Coral->getColor()),
                TextColumn::make('tanggal')
                    ->label('Date')
                    ->icon(Heroicon::Calendar)
                    ->iconColor('primary')
//                    ->color('primary')
                    ->date('D, d F Y')
                    ->summarize([
                        Count::make()->label('')
                            ->prefix('Total Hari: ')->suffix(' Hari')
                    ]),
                TextColumn::make('jam_bekerja')
                    ->alignCenter()
                    ->badge()
                    ->size(TextSize::Large)
//                    ->color('primary')
                    ->summarize([
                        Sum::make()
                            ->label('')
                            ->prefix('Total Jam: ')
                            ->suffix(' Jam'),
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
