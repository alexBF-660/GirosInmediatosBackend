<?php

namespace App\Filament\Resources\MovimientosAdministrativos\Pages;

use App\Filament\Resources\MovimientosAdministrativos\MovimientosAdministrativosResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;
use Filament\Actions\Action;

class ListMovimientosAdministrativos extends ListRecords
{
    protected static string $resource = MovimientosAdministrativosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('imprimirMovimientoAdministrativo')
                ->label('Imprimir Reporte de Movimientos Administrativos')
                ->color('info')
                ->icon('heroicon-o-printer')
                ->action(function () {
                    // Redirige a la ruta del PDF de países
                    return redirect()->away(
                        route('movimientosAdmPrint.print')
                    );
                })
                ->openUrlInNewTab(),
            CreateAction::make(),
        ];
    }
}
