<?php

namespace App\Filament\Resources\Cutis\Pages;

use App\Filament\Resources\Cutis\CutiResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCutis extends ListRecords
{
    protected static string $resource = CutiResource::class;
    protected ?string $heading = 'Pengajuan Cuti';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    /**
     * Fix polling dengan defer loading untuk custom query
     */
    public function isTableDeferLoaded(): bool
    {
        return false; // Disable defer loading agar polling berfungsi dengan custom query
    }

}
