<?php

namespace App\Filament\Resources\Giros\Widgets;

use App\Models\Giros;
use App\Models\Sucursales;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class GirosToDayOverview extends ChartWidget
{
    protected ?string $heading = 'Giros del día por sucursal';

    protected int|string|array $columnSpan = 2;

    public function getDescription(): ?string
    {
        return 'Cantidad de giros enviados hoy (' . now()->locale('es')->translatedFormat('d/m/Y') . ')';
    }

    protected function getData(): array
    {
        $sucursales = Sucursales::query()
            ->orderBy('nombre')
            ->get(['id', 'nombre']);

        $conteos = Giros::query()
            ->select('sucursal_origen_id', DB::raw('COUNT(*) as total'))
            ->whereDate('fecha_envio', today())
            ->groupBy('sucursal_origen_id')
            ->pluck('total', 'sucursal_origen_id');

        return [
            'datasets' => [
                [
                    'label' => 'Giros enviados',
                    'data' => $sucursales
                        ->map(fn (Sucursales $sucursal): int => (int) ($conteos[$sucursal->id] ?? 0))
                        ->values()
                        ->all(),
                    'backgroundColor' => '#f59e0b',
                    'borderColor' => '#d97706',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $sucursales->pluck('nombre')->all(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
