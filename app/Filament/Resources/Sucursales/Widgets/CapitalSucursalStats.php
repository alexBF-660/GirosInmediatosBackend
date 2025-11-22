<?php

namespace App\Filament\Resources\Sucursales\Widgets;

use App\Models\Sucursales;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CapitalSucursalStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $user = auth()->user();

        if (! $user || ! $user->sucursal_id) {
            return [
                Stat::make('Capital Actual', 'No disponible')
                    ->description('El usuario no tiene sucursal asignada')
                    ->color('gray'),
            ];
        }

        $sucursal = Sucursales::find($user->sucursal_id);

        if (! $sucursal) {
            return [
                Stat::make('Capital Actual', 'No disponible')
                    ->description('Sucursal no encontrada')
                    ->color('gray'),
            ];
        }

        $capital = $sucursal->capital_actual;

        // Color según capital
        $color =
            $capital > 8000 ? 'primary' :
            ($capital > 5000 ? 'info' :
            ($capital > 1000 ? 'warning' : 'danger'));

        return [
            Stat::make('Capital Actual', number_format($capital, 2) . ' Bs')
                ->description('Sucursal: ' . $sucursal->nombre) // descripción normal
                ->color($color) // aplica solo al número
                ->icon('heroicon-o-banknotes'),
        ];
    }
}
