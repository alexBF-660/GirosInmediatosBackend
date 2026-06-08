<?php

namespace App\Console\Commands;

use App\Services\RegenerarGirosService;
use Illuminate\Console\Command;

class RegenerarGirosCommand extends Command
{
    protected $signature = 'datos:regenerar-giros {--force : Confirma el borrado y la regeneración de datos de prueba}';

    protected $description = 'Elimina datos de prueba y regenera giros con movimientos de capital coherentes';

    public function handle(RegenerarGirosService $service): int
    {
        if (! $this->option('force')) {
            $this->error('Esta operación borra giros, movimientos de capital, distribuciones y movimientos administrativos.');
            $this->line('Ejecuta el comando con --force para continuar.');

            return self::FAILURE;
        }

        $this->warn('Regenerando datos de prueba desde cero...');

        $bar = $this->output->createProgressBar();
        $bar->setFormat(' %current% días procesados | %message%');
        $bar->setMessage('iniciando');
        $bar->start();

        $resultado = $service->ejecutar(function (string $fecha, int $girosDia, int $diasProcesados) use ($bar): void {
            $bar->setMessage("{$fecha} ({$girosDia} giros)");
            $bar->setProgress($diasProcesados);
        });

        $bar->finish();
        $this->newLine(2);

        $this->info('Regeneración completada.');
        $this->table(
            ['Indicador', 'Valor'],
            [
                ['Fecha inicio', $resultado['fecha_inicio']],
                ['Fecha fin', $resultado['fecha_fin']],
                ['Días hábiles', $resultado['dias_habiles']],
                ['Giros generados', $resultado['giros_generados']],
                ['Movimientos de capital', $resultado['movimientos_capital']],
            ]
        );

        $this->newLine();
        $this->info('Capital final por sucursal:');

        $filasCapitales = collect($resultado['capitales_finales'])
            ->map(fn (float $capital, string $nombre): array => [$nombre, number_format($capital, 2, '.', '') . ' Bs'])
            ->values()
            ->all();

        $this->table(['Sucursal', 'Capital actual'], $filasCapitales);

        return self::SUCCESS;
    }
}
