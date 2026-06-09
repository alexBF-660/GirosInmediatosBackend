<?php

namespace App\Console\Commands;

use App\Models\Sucursales;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class NormalizarCapitalSucursalesCommand extends Command
{
    protected $signature = 'datos:normalizar-capital-sucursales';

    protected $description = 'Ajusta el capital de cada sucursal a un monto variado entre 500 y 8000 Bs';

    public const CAPITAL_MIN = 500.0;

    public const CAPITAL_MAX = 8000.0;

    /** @var array<int, float> */
    private const CAPITALES_VARIADOS = [
        1 => 6247.35,  // La Paz
        2 => 1583.20,  // Rurrenabaque
        3 => 7891.50,  // Trinidad
        4 => 3412.80,  // Ixiamas
        5 => 5678.15,  // Reyes
        6 => 2156.90,  // Tumupasa
        7 => 7123.40,  // Guanay
        8 => 4890.25,  // Mapiri
        9 => 3765.60,  // Teoponte
        10 => 6534.75, // Mayaya
    ];

    public static function capitalObjetivo(int $sucursalId): float
    {
        if (isset(self::CAPITALES_VARIADOS[$sucursalId])) {
            return self::CAPITALES_VARIADOS[$sucursalId];
        }

        $hash = abs(crc32('capital-sucursal-' . $sucursalId));
        $spread = self::CAPITAL_MAX - self::CAPITAL_MIN;
        $factor = (($hash % 1000) + (($hash >> 10) % 1000)) / 2000;

        return round(self::CAPITAL_MIN + $spread * $factor, 2);
    }

    public function handle(): int
    {
        $sucursales = Sucursales::query()->orderBy('id')->get(['id', 'nombre']);

        foreach ($sucursales as $sucursal) {
            $objetivo = self::capitalObjetivo((int) $sucursal->id);

            $ultimo = DB::table('movimiento_capital')
                ->where('sucursal_id', $sucursal->id)
                ->orderByDesc('fecha')
                ->orderByDesc('id')
                ->first(['capital_actual']);

            if ($ultimo !== null) {
                $offset = $objetivo - (float) $ultimo->capital_actual;

                DB::table('movimiento_capital')
                    ->where('sucursal_id', $sucursal->id)
                    ->update([
                        'capital_inicial' => DB::raw('capital_inicial + ' . $offset),
                        'capital_actual' => DB::raw('capital_actual + ' . $offset),
                    ]);
            }

            Sucursales::query()
                ->whereKey($sucursal->id)
                ->update(['capital_actual' => $objetivo]);

            $this->line("{$sucursal->nombre}: {$objetivo} Bs");
        }

        $this->info('Capitales normalizados. Exporte datos LSTM: php artisan lstm:export-movimientos');

        return self::SUCCESS;
    }
}
