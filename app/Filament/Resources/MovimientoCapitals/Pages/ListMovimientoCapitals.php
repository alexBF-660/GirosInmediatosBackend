<?php

namespace App\Filament\Resources\MovimientoCapitals\Pages;

use App\Filament\Resources\MovimientoCapitals\MovimientoCapitalResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMovimientoCapitals extends ListRecords
{
    protected static string $resource = MovimientoCapitalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
