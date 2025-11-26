<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Hidden;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('imprimirUsuarios')
                ->label('Imprimir Usuarios')
                ->color('info')
                ->icon('heroicon-o-printer')
                ->visible(fn () => !Auth::user()->hasRole('Operador de sucursal')) // Oculta para operadores
                ->form(function () {
                    $user = Auth::user();

                    if ($user->hasRole('Gerente de sucursal')) {
                        // Select oculto con sucursal fija del gerente
                        return [
                            Hidden::make('sucursal_id')
                                ->default($user->sucursal_id)
                                ->required(),
                        ];
                    } else {
                        // Usuarios con otros roles → select con todas las sucursales + opción "Todos"
                        return [
                            Select::make('sucursal_id')
                                ->label('Sucursal')
                                ->options([0 => 'Todos'] + \App\Models\Sucursales::pluck('nombre', 'id')->toArray())
                                ->searchable()
                                ->preload()
                                ->required(),
                        ];
                    }
                })
                ->action(function (array $data) {
                    // Redirige a la ruta del PDF según el sucursal_id seleccionado o del gerente
                    return redirect()->away(
                        route('usuarios.print', $data['sucursal_id'])
                    );
                })
                ->openUrlInNewTab(),
            CreateAction::make()
                ->icon('heroicon-o-user-plus')
                ->label('Crear Usuario'),
        ];
    }
}
