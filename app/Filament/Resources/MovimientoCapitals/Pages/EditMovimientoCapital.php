<?php

namespace App\Filament\Resources\MovimientoCapitals\Pages;

use App\Filament\Resources\MovimientoCapitals\MovimientoCapitalResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMovimientoCapital extends EditRecord
{
    protected static string $resource = MovimientoCapitalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
