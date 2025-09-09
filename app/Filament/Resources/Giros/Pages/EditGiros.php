<?php

namespace App\Filament\Resources\Giros\Pages;

use App\Filament\Resources\Giros\GirosResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditGiros extends EditRecord
{
    protected static string $resource = GirosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
