<?php

namespace App\Filament\Resources\EstadoGiros\Pages;

use App\Filament\Resources\EstadoGiros\EstadoGirosResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEstadoGiros extends EditRecord
{
    protected static string $resource = EstadoGirosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
