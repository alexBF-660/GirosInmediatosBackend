<?php

namespace App\Filament\Resources\DistribucionCapitals\Pages;

use App\Filament\Resources\DistribucionCapitals\DistribucionCapitalResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;
use Filament\Actions\Action;

class ListDistribucionCapitals extends ListRecords
{
    protected static string $resource = DistribucionCapitalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('imprimirDistribucion')
                ->label('Imprimir Reporte de Distribucion de capital')
                ->color('info')
                ->icon('heroicon-o-printer'),
            CreateAction::make(),
        ];
    }
}
