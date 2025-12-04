<?php

namespace App\Filament\Resources\Sucursales\Pages;

use App\Filament\Resources\Sucursales\SucursalesResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;
use Filament\Actions\Action;

class ListSucursales extends ListRecords
{
    protected static string $resource = SucursalesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('imprimirSucursales')
                ->label('Imprimir Reporte de sucursales')
                ->color('info')
                ->icon('heroicon-o-printer')
                ->action(function () {
                    // Redirige a la ruta del PDF de países
                    return redirect()->away(
                        route('sucursalesPrint.print')
                    );
                })
                ->openUrlInNewTab(),
            CreateAction::make(),
        ];
    }
}
