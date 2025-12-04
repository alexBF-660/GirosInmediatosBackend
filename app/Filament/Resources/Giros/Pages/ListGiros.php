<?php

namespace App\Filament\Resources\Giros\Pages;

use App\Filament\Resources\Giros\GirosResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;
use App\Models\Sucursales;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\DatePicker;


class ListGiros extends ListRecords
{
    protected static string $resource = GirosResource::class;

    protected function getHeaderActions(): array
    {
        return [

            Action::make('imprimirReporteFechas')
                ->label('Imprimir Reporte por Fechas')
                ->color('info')
                ->icon('heroicon-o-printer')
                ->form(function () {
                    $user = Auth::user();

                    if ($user->hasRole('Gerente de sucursal')) {
                        // Select oculto con sucursal fija del gerente
                        return [
                            Hidden::make('sucursal_id')
                                ->default($user->sucursal_id)
                                ->required(),
                            DatePicker::make('fecha_inicio')
                                ->label('Seleccione la fecha de inicio'),
                            DatePicker::make('fecha_fin')
                                ->label('Seleccione la fecha de fin'),
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
                            DatePicker::make('fecha_inicio')
                                ->label('Seleccione la fecha de inicio'),
                            DatePicker::make('fecha_fin')
                                ->label('Seleccione la fecha de fin'),
                        ];
                    }
                })
                ->action(function (array $data) {
                    // Redirige a la ruta del PDF según el sucursal_id seleccionado o del gerente
                    return redirect()->away(
                        route('giroRango.print', $data)
                    );
                })
                ->openUrlInNewTab(),

            Action::make('imprimirReporteDiario')
                ->label('Imprimir Reporte Diario')
                ->color('danger')
                ->icon('heroicon-o-printer')
                ->form(function () {
                    $user = Auth::user();

                    if ($user->hasRole('Gerente de sucursal')) {
                        // Select oculto con sucursal fija del gerente
                        return [
                            Hidden::make('sucursal_id')
                                ->default($user->sucursal_id)
                                ->required(),
                            DatePicker::make('fecha')
                                ->label('Seleccione la fecha del reporte'),
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
                            DatePicker::make('fecha')
                                ->label('Seleccione la fecha del reporte'),
                        ];
                    }
                })
                ->action(function (array $data) {
                    // Redirige a la ruta del PDF según el sucursal_id seleccionado o del gerente
                    return redirect()->away(
                        route('giroDiario.print', $data)
                    );
                })
                ->openUrlInNewTab(),

            CreateAction::make(),
        ];
    }
    protected function getHeaderWidgets(): array
    {
        $user = auth()->user();

        // Verifica roles
        if ($user->hasRole(['Gerente de sucursal', 'Operador de sucursal'])) {
            $this->notifyCapitalLevel();
            return [
                \App\Filament\Resources\Sucursales\Widgets\CapitalSucursalStats::class,
            ];
        }

        // Si el usuario no tiene rol permitido, no mostrar nada
        return [];
    }

    protected function notifyCapitalLevel(): void
    {
        $user = auth()->user();

        $sucursal = Sucursales::find($user->sucursal_id);
        if (! $sucursal) {
            return;
        }

        $capital = $sucursal->capital_actual;

        // Determinar mensaje y tipo según capital
        if ($capital > 8000) {
            $message = 'Nivel de capital: Estable';
            $color = 'success';
        } elseif ($capital > 5000) {
            $message = 'Nivel de capital: Regular';
            $color = 'info';
        } elseif ($capital > 1000) {
            $message = 'Nivel de capital: Bajo';
            $color = 'warning';
        } else {
            $message = 'Nivel de capital: Muy bajo';
            $color = 'danger';
        }

        Notification::make()
            ->title('Atención al capital')
            ->body($message)
            ->color($color)
            ->send();
    }
}
