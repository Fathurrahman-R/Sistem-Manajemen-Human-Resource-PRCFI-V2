<?php

namespace App\Filament\Resources\Master\Programs\Pages;

use App\Filament\Resources\Master\Programs\ProgramResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManagePrograms extends ManageRecords
{
    protected static string $resource = ProgramResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
