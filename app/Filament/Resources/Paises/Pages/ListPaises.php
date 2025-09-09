<?php

namespace App\Filament\Resources\Paises\Pages;

use App\Filament\Resources\Paises\PaisesResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPaises extends ListRecords
{
    protected static string $resource = PaisesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
