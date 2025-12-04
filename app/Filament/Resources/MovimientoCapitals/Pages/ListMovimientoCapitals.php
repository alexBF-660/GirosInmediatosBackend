<?php

namespace App\Filament\Resources\MovimientoCapitals\Pages;

use App\Filament\Resources\MovimientoCapitals\MovimientoCapitalResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;
use Filament\Actions\Action;

class ListMovimientoCapitals extends ListRecords
{
    protected static string $resource = MovimientoCapitalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('imprimirMovimientoCapital')
                ->label('Imprimir Reporte de Movimiento de Capital')
                ->color('info')
                ->icon('heroicon-o-printer'),
            CreateAction::make(),
        ];
    }
}
