<?php

namespace App\Filament\Resources\Paises\Pages;

use App\Filament\Resources\Paises\PaisesResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPaises extends EditRecord
{
    protected static string $resource = PaisesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
