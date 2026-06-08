<?php

namespace App\Filament\Resources\Departamentos\Pages;

use App\Filament\Resources\Departamentos\DepartamentoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;
use Filament\Actions\Action;


class ListDepartamentos extends ListRecords
{
    protected static string $resource = DepartamentoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('imprimirDepartamentos')
                ->label('Imprimir Departamento')
                ->color('info')
                ->icon('heroicon-o-printer')
                ->url(route('departamentosPrint.print'))
                ->openUrlInNewTab(),
            CreateAction::make(),
        ];
    }
}
