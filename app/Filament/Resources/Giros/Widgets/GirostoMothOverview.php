<?php

namespace App\Filament\Resources\Giros\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Giros;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GirostoMothOverview extends ChartWidget
{
    protected ?string $heading = 'Cantidad de Giros por Mes (Año Actual)';
    protected int|string|array $columnSpan = 2;

    protected function getData(): array
    {
        $year = Carbon::now()->year;

        // Consulta adaptada para PostgreSQL
        $girosPorMes = Giros::select(
                DB::raw("DATE_PART('month', created_at) AS mes"),
                DB::raw('COUNT(*) AS total')
            )
            ->whereYear('created_at', $year)
            ->groupBy(DB::raw("DATE_PART('month', created_at)"))
            ->orderBy(DB::raw("DATE_PART('month', created_at)"))
            ->pluck('total', 'mes');

        // Lista de meses
        $meses = [
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre',
        ];

        // Completar con 0 los meses sin giros
        $datos = [];
        foreach ($meses as $num => $nombre) {
            $datos[] = $girosPorMes[$num] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Giros Registrados',
                    'data' => $datos,
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.2)',
                    'tension' => 0.4,
                    'fill' => true,
                ],
            ],
            'labels' => array_values($meses),
        ];
    }

    protected function getType(): string
    {
        return 'line'; // Puedes usar 'bar' si prefieres barras
    }

        protected function getColumns(): int|array
    {
        return [
            'default' => 1, // en pantallas pequeñas (celular)
            'sm' => 1,      // en pantallas medianas
            'lg' => 1,      // en pantallas grandes → 3 por fila
        ];
    }
}
