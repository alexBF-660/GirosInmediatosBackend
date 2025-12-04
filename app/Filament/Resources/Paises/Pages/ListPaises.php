<?php

namespace App\Filament\Resources\Paises\Pages;

use App\Filament\Resources\Paises\PaisesResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;
use Filament\Actions\Action;

class ListPaises extends ListRecords
{
    protected static string $resource = PaisesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('imprimirPaises')
                ->label('Imprimir Reporte de Países')
                ->color('info')
                ->icon('heroicon-o-printer')
                ->action(function () {
                    // Redirige a la ruta del PDF de países
                    return redirect()->away(
                        route('paisesPrint.print')
                    );
                })
                ->openUrlInNewTab(),
            CreateAction::make(),
        ];
    }
}
