<?php

namespace App\Filament\Resources\Timesheets\Pages;

use App\Filament\Resources\Timesheets\RelationManagers\CutiRelationManager;
use App\Filament\Resources\Timesheets\RelationManagers\IsiTimesheetRelationManager;
use App\Filament\Resources\Timesheets\RelationManagers\TimesheetAktifitasRelationManager;
use App\Filament\Resources\Timesheets\RelationManagers\TimesheetKehadiranRelationManager;
use App\Filament\Resources\Timesheets\TimesheetResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTimesheet extends ViewRecord
{
    protected static string $resource = TimesheetResource::class;
    protected function getAllRelationManagers(): array
    {
        return [
            TimesheetKehadiranRelationManager::class,
            TimesheetAktifitasRelationManager::class,
//            IsiTimesheetRelationManager::class,
            CutiRelationManager::class
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
