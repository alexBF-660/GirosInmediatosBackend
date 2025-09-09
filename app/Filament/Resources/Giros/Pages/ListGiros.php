<?php

namespace App\Filament\Resources\Giros\Pages;

use App\Filament\Resources\Giros\GirosResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGiros extends ListRecords
{
    protected static string $resource = GirosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
