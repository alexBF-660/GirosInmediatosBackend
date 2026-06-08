<?php

namespace App\Console\Commands;

use App\Models\MovimientoCapital;
use App\Models\Sucursales;
use Illuminate\Console\Command;
use RuntimeException;

class ExportMovimientoCapitalLstmCommand extends Command
{
    protected $signature = 'lstm:export-movimientos
                            {--output= : Ruta del CSV de salida (default: ../lstm_api_flask/data/movimiento_capital_latest.csv)}';

    protected $description = 'Exporta movimiento_capital a CSV para entrenar los modelos LSTM';

    public function handle(): int
    {
        $outputPath = $this->option('output')
            ?: base_path('../lstm_api_flask/data/movimiento_capital_latest.csv');

        $directory = dirname($outputPath);

        if (! is_dir($directory) && ! mkdir($directory, 0755, true) && ! is_dir($directory)) {
            throw new RuntimeException("No se pudo crear el directorio: {$directory}");
        }

        $handle = fopen($outputPath, 'w');

        if ($handle === false) {
            $this->error("No se pudo escribir en: {$outputPath}");

            return self::FAILURE;
        }

        fputcsv($handle, [
            'id',
            'fecha',
            'sucursal_id',
            'total_enviado',
            'total_recibido',
            'balance_dia',
            'capital_inicial',
            'capital_actual',
            'created_at',
            'updated_at',
            'deleted_at',
        ]);

        $totalFilas = 0;
        $fechaMin = null;
        $fechaMax = null;

        MovimientoCapital::query()
            ->orderBy('sucursal_id')
            ->orderBy('fecha')
            ->orderBy('id')
            ->chunk(500, function ($movimientos) use ($handle, &$totalFilas, &$fechaMin, &$fechaMax): void {
                foreach ($movimientos as $movimiento) {
                    fputcsv($handle, [
                        $movimiento->id,
                        $movimiento->fecha?->format('Y-m-d') ?? $movimiento->fecha,
                        $movimiento->sucursal_id,
                        $movimiento->total_enviado,
                        $movimiento->total_recibido,
                        $movimiento->balance_dia,
                        $movimiento->capital_inicial,
                        $movimiento->capital_actual,
                        $movimiento->created_at,
                        $movimiento->updated_at,
                        $movimiento->deleted_at,
                    ]);

                    $totalFilas++;
                    $fecha = (string) ($movimiento->fecha?->format('Y-m-d') ?? $movimiento->fecha);

                    if ($fechaMin === null || $fecha < $fechaMin) {
                        $fechaMin = $fecha;
                    }

                    if ($fechaMax === null || $fecha > $fechaMax) {
                        $fechaMax = $fecha;
                    }
                }
            });

        fclose($handle);

        $conteoPorSucursal = MovimientoCapital::query()
            ->selectRaw('sucursal_id, COUNT(*) as total')
            ->groupBy('sucursal_id')
            ->pluck('total', 'sucursal_id');

        $this->info("Exportación completada: {$outputPath}");
        $this->table(
            ['Indicador', 'Valor'],
            [
                ['Filas exportadas', $totalFilas],
                ['Fecha mínima', $fechaMin ?? '-'],
                ['Fecha máxima', $fechaMax ?? '-'],
                ['Sucursales con datos', $conteoPorSucursal->count()],
            ]
        );

        $this->newLine();
        $this->info('Registros por sucursal:');

        $filas = Sucursales::query()
            ->orderBy('id')
            ->get(['id', 'nombre'])
            ->map(function ($sucursal) use ($conteoPorSucursal) {
                $total = (int) ($conteoPorSucursal[$sucursal->id] ?? 0);
                $estado = $total >= 35 ? 'OK' : 'INSUFICIENTE (<35)';

                return [$sucursal->id, $sucursal->nombre, $total, $estado];
            })
            ->all();

        $this->table(['ID', 'Sucursal', 'Registros', 'Estado LSTM'], $filas);

        $insuficientes = collect($filas)->filter(fn (array $fila): bool => $fila[3] !== 'OK');

        if ($insuficientes->isNotEmpty()) {
            $this->warn('Algunas sucursales no alcanzan los 35 registros mínimos para predicción LSTM.');

            return self::FAILURE;
        }

        $this->newLine();
        $this->line('Siguiente paso (reentrenamiento):');
        $this->line('  cd ../lstm_api_flask');
        $this->line('  .\\venv\\Scripts\\activate');
        $this->line('  cd model');
        $this->line('  python train_model.py --csv ../data/movimiento_capital_latest.csv');

        return self::SUCCESS;
    }
}
