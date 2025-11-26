<?php

namespace App\Filament\Resources\MovimientosAdministrativos\Pages;

use App\Filament\Resources\MovimientosAdministrativos\MovimientosAdministrativosResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMovimientosAdministrativos extends EditRecord
{
    protected static string $resource = MovimientosAdministrativosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
