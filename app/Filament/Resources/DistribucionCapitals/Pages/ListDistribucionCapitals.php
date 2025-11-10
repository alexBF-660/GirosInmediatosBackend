<?php

namespace App\Filament\Resources\DistribucionCapitals\Pages;

use App\Filament\Resources\DistribucionCapitals\DistribucionCapitalResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDistribucionCapitals extends ListRecords
{
    protected static string $resource = DistribucionCapitalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
