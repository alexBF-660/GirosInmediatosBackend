<?php

namespace App\Filament\Resources\EstadoGiros\Pages;

use App\Filament\Resources\EstadoGiros\EstadoGirosResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEstadoGiros extends ListRecords
{
    protected static string $resource = EstadoGirosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
