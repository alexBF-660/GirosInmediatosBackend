<?php

namespace App\Filament\Resources\EstadoGiros\Pages;

use App\Filament\Resources\EstadoGiros\EstadoGirosResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;
use Filament\Actions\Action;

class ListEstadoGiros extends ListRecords
{
    protected static string $resource = EstadoGirosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('imprimirEstadoGiros')
                ->label('Imprimir Reporte de Estado de Giros')
                ->color('info')
                ->icon('heroicon-o-printer')
                ->action(function () {
                    // Redirige a la ruta del PDF de países
                    return redirect()->away(
                        route('estadoGirosPrint.print')
                    );
                })
                ->openUrlInNewTab(),
            CreateAction::make(),
        ];
    }
}
