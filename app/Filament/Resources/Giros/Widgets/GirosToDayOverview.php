<?php

namespace App\Filament\Resources\Giros\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Sucursales;
use App\Models\Giros;
use Illuminate\Support\Facades\DB;

class GirosToDayOverview extends StatsOverviewWidget
{
    protected ?string $heading = 'Giros realizados el dia de hoy';

    protected function getStats(): array
    {
        // Fecha fija de ejemplo
        $fecha = '2025-10-11';

        // Obtenemos todas las sucursales
        $sucursales = Sucursales::select('id', 'nombre')->get();

        // Obtenemos los giros del día agrupados por sucursal
        $girosPorSucursal = Giros::select('sucursal_origen_id', DB::raw('COUNT(*) as total'))
            ->whereDate('created_at', $fecha)
            ->groupBy('sucursal_origen_id')
            ->pluck('total', 'sucursal_origen_id'); // clave = id sucursal, valor = total

        // Creamos los Stats, incluyendo las sucursales sin giros
        $stats = [];

        foreach ($sucursales as $sucursal) {
            $total = $girosPorSucursal[$sucursal->id] ?? 0; // si no hay giros, usa 0

            $stats[] = Stat::make("Giros de {$sucursal->nombre}", $total)
                ->description("Fecha: {$fecha}")
                ->color('gray');
        }

        return $stats;
    }

    protected function getColumns(): int|array
    {
        return [
            'default' => 1, // en pantallas pequeñas (celular)
            'sm' => 2,      // en pantallas medianas
            'lg' => 3,      // en pantallas grandes → 3 por fila
        ];
    }
}
