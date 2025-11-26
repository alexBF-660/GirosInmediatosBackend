<?php

namespace App\Filament\Resources\MovimientosAdministrativos\Pages;

use App\Filament\Resources\MovimientosAdministrativos\MovimientosAdministrativosResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMovimientosAdministrativos extends ListRecords
{
    protected static string $resource = MovimientosAdministrativosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
