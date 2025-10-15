<?php

namespace App\Filament\Resources\Sucursales\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Sucursales;

class SucursalCapitalOverview extends ChartWidget
{
    protected ?string $heading = 'Capítal actual de las sucursales';
    protected int|string|array $columnSpan = 2;

    protected function getData(): array
    {

        $sucursales = Sucursales::select('nombre', 'capital_actual')->get();

        $labels = $sucursales->pluck('nombre')->toArray();
        $data = $sucursales->pluck('capital_actual')->toArray();
        return [
            'datasets' => [
                [
                    'label' => 'Capital Actual',
                    'data' => $data,
                    'backgroundColor' => '#3b82f6', // azul Filament
                    'borderColor' => '#1d4ed8',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
