<?php

namespace App\Console\Commands;

use App\Models\Sucursales;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CorregirCapitalMovimientosCommand extends Command
{
    protected $signature = 'datos:corregir-capital-movimientos';

    protected $description = 'Recalcula capital_actual en movimiento_capital con piso mínimo y sincroniza sucursales';

    private const CAPITAL_INICIAL = 10000;

    private const CAPITAL_MINIMO = 5000;

    public function handle(): int
    {
        $sucursales = Sucursales::query()->orderBy('id')->get(['id', 'nombre']);

        foreach ($sucursales as $sucursal) {
            $movimientos = DB::table('movimiento_capital')
                ->where('sucursal_id', $sucursal->id)
                ->orderBy('fecha')
                ->orderBy('id')
                ->get(['id', 'balance_dia']);

            if ($movimientos->isEmpty()) {
                $this->warn("Sucursal {$sucursal->nombre}: sin movimientos.");

                continue;
            }

            $capital = (float) self::CAPITAL_INICIAL;

            foreach ($movimientos as $movimiento) {
                $capitalInicial = $capital;
                $capital = max(
                    (float) self::CAPITAL_MINIMO,
                    $capitalInicial + (float) $movimiento->balance_dia
                );

                DB::table('movimiento_capital')
                    ->where('id', $movimiento->id)
                    ->update([
                        'capital_inicial' => round($capitalInicial, 2),
                        'capital_actual' => round($capital, 2),
                    ]);
            }

            Sucursales::query()
                ->whereKey($sucursal->id)
                ->update(['capital_actual' => round($capital, 2)]);

            $this->line("{$sucursal->nombre}: capital final {$capital} Bs");
        }

        $this->info('Capitales corregidos. Exporte y reentrene LSTM: php artisan lstm:export-movimientos');

        return self::SUCCESS;
    }
}
