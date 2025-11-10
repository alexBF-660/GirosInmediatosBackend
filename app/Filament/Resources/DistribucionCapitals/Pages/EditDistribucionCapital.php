<?php

namespace App\Filament\Resources\DistribucionCapitals\Pages;

use App\Filament\Resources\DistribucionCapitals\DistribucionCapitalResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDistribucionCapital extends EditRecord
{
    protected static string $resource = DistribucionCapitalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
