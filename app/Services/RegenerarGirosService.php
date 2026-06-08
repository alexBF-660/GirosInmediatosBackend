<?php

namespace App\Services;

use App\Models\Giros;
use App\Models\MovimientoCapital;
use App\Models\Sucursales;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class RegenerarGirosService
{
    private const CAPITAL_INICIAL = 10000;

    private const FECHA_FIN = '2026-06-09';

    private const MONTO_MIN = 100;

    private const MONTO_MAX = 500;

    private const VOLUMEN_DIA_MIN = 3000;

    private const VOLUMEN_DIA_MAX = 10000;

    private const GIROS_DIA_MIN = 20;

    private const GIROS_DIA_MAX = 50;

    private const ESTADO_ENTREGADO_ID = 1;

    /** @var array<int, int> */
    private array $pesosSucursal = [
        1 => 4,
        2 => 4,
        7 => 4,
        10 => 4,
        3 => 1,
        4 => 1,
        5 => 1,
        6 => 1,
        8 => 1,
        9 => 1,
    ];

    /** @var array<int, int> */
    private array $pesosDestino = [
        1 => 4,
        2 => 4,
        7 => 4,
        10 => 4,
        3 => 1,
        4 => 1,
        5 => 1,
        6 => 1,
        8 => 1,
        9 => 1,
    ];

    private const CAPITAL_MINIMO_FINAL = 5000;

    /** @var array<int, string> */
    private array $nombres = [
        'Juan', 'María', 'Carlos', 'Lucía', 'Pedro', 'Ana', 'José', 'Rosa',
        'Miguel', 'Elena', 'Diego', 'Patricia', 'Fernando', 'Gabriela', 'Luis',
        'Andrea', 'Jorge', 'Claudia', 'Ricardo', 'Sonia', 'Marcos', 'Verónica',
    ];

    /** @var array<int, string> */
    private array $apellidos = [
        'Mamani', 'Quispe', 'Choque', 'Flores', 'Apaza', 'Condori', 'Huanca',
        'Vargas', 'Rojas', 'Gutiérrez', 'Salazar', 'Fernández', 'Torrez', 'Aguilar',
        'Montaño', 'Cáceres', 'Paredes', 'Bustillos', 'Callisaya', 'Laura',
    ];

    /** @var array<int, string> */
    private array $extensionesCi = ['LP', 'SC', 'CB', 'OR', 'PT', 'TJ', 'CH', 'BE'];

    /** @var array<int, int> */
    private array $sucursalIds = [];

    /** @var array<int, array<int, int>> */
    private array $operadoresPorSucursal = [];

    public function ejecutar(?callable $onProgress = null): array
    {
        $this->cargarContexto();

        return DB::transaction(function () use ($onProgress) {
            $this->limpiarDatos();

            $fechaInicio = Carbon::parse(self::FECHA_FIN)->subYears(5);
            $fechaFin = Carbon::parse(self::FECHA_FIN);

            $totalGiros = 0;
            $diasProcesados = 0;
            $lote = [];
            $periodo = CarbonPeriod::create($fechaInicio, $fechaFin);

            foreach ($periodo as $fecha) {
                if ($fecha->isSunday()) {
                    continue;
                }

                $girosDia = $this->generarGirosDelDia($fecha);
                $totalGiros += count($girosDia);
                array_push($lote, ...$girosDia);

                if (count($lote) >= 1000) {
                    $this->insertarGiros($lote);
                    $lote = [];
                }

                $diasProcesados++;

                if ($onProgress !== null) {
                    $onProgress($fecha->toDateString(), count($girosDia), $diasProcesados);
                }
            }

            if ($lote !== []) {
                $this->insertarGiros($lote);
            }

            $this->reconstruirMovimientosCapital($fechaInicio, $fechaFin);

            $capitalesFinales = Sucursales::query()
                ->orderBy('nombre')
                ->pluck('capital_actual', 'nombre')
                ->all();

            return [
                'fecha_inicio' => $fechaInicio->toDateString(),
                'fecha_fin' => $fechaFin->toDateString(),
                'dias_habiles' => $diasProcesados,
                'giros_generados' => $totalGiros,
                'movimientos_capital' => MovimientoCapital::count(),
                'capitales_finales' => $capitalesFinales,
            ];
        });
    }

    public static function calcularComision(float $monto): float
    {
        $tasa = $monto >= 500 ? 0.02 : 0.04;

        return round($monto * $tasa, 2);
    }

    private function cargarContexto(): void
    {
        $this->sucursalIds = Sucursales::query()->orderBy('id')->pluck('id')->all();

        if ($this->sucursalIds === []) {
            throw new RuntimeException('No hay sucursales registradas.');
        }

        $operadores = User::role('Operador de sucursal')
            ->whereNotNull('sucursal_id')
            ->get(['id', 'sucursal_id']);

        foreach ($this->sucursalIds as $sucursalId) {
            $ids = $operadores
                ->where('sucursal_id', $sucursalId)
                ->pluck('id')
                ->all();

            if ($ids === []) {
                throw new RuntimeException("La sucursal {$sucursalId} no tiene operador asignado.");
            }

            $this->operadoresPorSucursal[$sucursalId] = $ids;
        }
    }

    private function limpiarDatos(): void
    {
        DB::table('movimientos_administrativos')->truncate();
        DB::table('distribucion_capital')->truncate();
        DB::table('movimiento_capital')->truncate();
        DB::table('giros')->truncate();

        Sucursales::query()->update(['capital_actual' => self::CAPITAL_INICIAL]);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function generarGirosDelDia(Carbon $fecha): array
    {
        $cantidad = $fecha->isSaturday()
            ? random_int((int) (self::GIROS_DIA_MIN * 0.5), (int) (self::GIROS_DIA_MAX * 0.5))
            : random_int(self::GIROS_DIA_MIN, self::GIROS_DIA_MAX);

        $giros = [];

        for ($i = 0; $i < $cantidad; $i++) {
            $origenId = $this->elegirSucursalPonderada();
            $destinoId = $this->elegirDestinoDistinto($origenId);
            $monto = $this->generarMonto($origenId);
            $fechaEnvio = $fecha->toDateString();
            $fechaEntrega = $this->calcularFechaEntrega($fecha);
            $createdAt = $this->generarDateTimeOperacion($fecha);
            $operadorOrigen = $this->operadoresPorSucursal[$origenId][array_rand($this->operadoresPorSucursal[$origenId])];
            $operadorDestino = $this->operadoresPorSucursal[$destinoId][array_rand($this->operadoresPorSucursal[$destinoId])];

            $giros[] = [
                'nombre_remitente' => $this->generarNombreCompleto(),
                'nombre_consignatario' => $this->generarNombreCompleto(),
                'monto_enviado' => $monto,
                'comision_envio' => self::calcularComision($monto),
                'fecha_envio' => $fechaEnvio,
                'fecha_entrega' => $fechaEntrega,
                'ci_consignatario' => $this->generarCiBoliviano(),
                'sucursal_origen_id' => $origenId,
                'sucursal_destino_id' => $destinoId,
                'usuario_envio_id' => $operadorOrigen,
                'usuario_entrega_id' => $operadorDestino,
                'estado_id' => self::ESTADO_ENTREGADO_ID,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
                'deleted_at' => null,
            ];
        }

        $this->ajustarVolumenDiario($giros);

        foreach ($giros as &$giro) {
            $giro['comision_envio'] = self::calcularComision((float) $giro['monto_enviado']);
        }

        unset($giro);

        return $giros;
    }

    /**
     * @param  array<int, array<string, mixed>>  $giros
     */
    private function ajustarVolumenDiario(array &$giros): void
    {
        if ($giros === []) {
            return;
        }

        $objetivo = (float) random_int(self::VOLUMEN_DIA_MIN, self::VOLUMEN_DIA_MAX);

        for ($intento = 0; $intento < 200; $intento++) {
            $suma = array_sum(array_column($giros, 'monto_enviado'));

            if ($suma >= self::VOLUMEN_DIA_MIN && $suma <= self::VOLUMEN_DIA_MAX) {
                return;
            }

            $indice = array_rand($giros);

            if ($suma < self::VOLUMEN_DIA_MIN) {
                $incremento = min(
                    self::MONTO_MAX - (float) $giros[$indice]['monto_enviado'],
                    max(10, ($objetivo - $suma) / max(1, count($giros) / 2))
                );
                $giros[$indice]['monto_enviado'] = round((float) $giros[$indice]['monto_enviado'] + $incremento, 2);
            } else {
                $decremento = min(
                    (float) $giros[$indice]['monto_enviado'] - self::MONTO_MIN,
                    max(10, ($suma - $objetivo) / max(1, count($giros) / 2))
                );
                $giros[$indice]['monto_enviado'] = round((float) $giros[$indice]['monto_enviado'] - $decremento, 2);
            }

            $giros[$indice]['monto_enviado'] = min(self::MONTO_MAX, max(self::MONTO_MIN, (float) $giros[$indice]['monto_enviado']));
        }
    }

    private function generarMonto(int $sucursalOrigenId): float
    {
        $esActiva = in_array($sucursalOrigenId, [1, 2, 7, 10], true);

        if ($esActiva) {
            return (float) random_int(250, 500);
        }

        return (float) random_int(100, 300);
    }

    private function elegirSucursalPonderada(?array $pesos = null): int
    {
        $pesos ??= $this->pesosSucursal;
        $opciones = [];

        foreach ($this->sucursalIds as $sucursalId) {
            $peso = $pesos[$sucursalId] ?? 1;

            for ($i = 0; $i < $peso; $i++) {
                $opciones[] = $sucursalId;
            }
        }

        return $opciones[array_rand($opciones)];
    }

    private function elegirDestinoDistinto(int $origenId): int
    {
        do {
            $destinoId = $this->elegirSucursalPonderada($this->pesosDestino);
        } while ($destinoId === $origenId);

        return $destinoId;
    }

    private function generarNombreCompleto(): string
    {
        $nombre = $this->nombres[array_rand($this->nombres)];
        $apellidoPaterno = $this->apellidos[array_rand($this->apellidos)];
        $apellidoMaterno = $this->apellidos[array_rand($this->apellidos)];

        return "{$nombre} {$apellidoPaterno} {$apellidoMaterno}";
    }

    private function generarCiBoliviano(): string
    {
        $ci = (string) random_int(1000000, 9999999);

        if (random_int(0, 1) === 1) {
            $extension = $this->extensionesCi[array_rand($this->extensionesCi)];

            return "{$ci} {$extension}";
        }

        return $ci;
    }

    private function calcularFechaEntrega(Carbon $fechaEnvio): string
    {
        $sorteo = random_int(1, 100);

        if ($sorteo <= 80) {
            return $fechaEnvio->toDateString();
        }

        if ($sorteo <= 95) {
            return $this->sumarDiasHabiles($fechaEnvio, 1)->toDateString();
        }

        return $this->sumarDiasHabiles($fechaEnvio, random_int(2, 3))->toDateString();
    }

    private function sumarDiasHabiles(Carbon $fecha, int $dias): Carbon
    {
        $resultado = $fecha->copy();
        $agregados = 0;

        while ($agregados < $dias) {
            $resultado->addDay();

            if (! $resultado->isSunday()) {
                $agregados++;
            }
        }

        return $resultado;
    }

    private function generarDateTimeOperacion(Carbon $fecha): string
    {
        $horaMaxima = $fecha->isSaturday() ? 12 : 18;
        $hora = random_int(8, max(8, $horaMaxima - 1));
        $minuto = random_int(0, 59);
        $segundo = random_int(0, 59);

        return $fecha->copy()->setTime($hora, $minuto, $segundo)->toDateTimeString();
    }

    /**
     * @param  array<int, array<string, mixed>>  $giros
     */
    private function insertarGiros(array $giros): void
    {
        Giros::withoutEvents(function () use ($giros): void {
            foreach (array_chunk($giros, 500) as $chunk) {
                DB::table('giros')->insert($chunk);
            }
        });
    }

    private function reconstruirMovimientosCapital(Carbon $fechaInicio, Carbon $fechaFin): void
    {
        /** @var array<int, float> $capitales */
        $capitales = array_fill_keys($this->sucursalIds, (float) self::CAPITAL_INICIAL);

        $envios = DB::table('giros')
            ->select('fecha_envio', 'sucursal_origen_id', DB::raw('SUM(monto_enviado) as total'))
            ->whereBetween('fecha_envio', [$fechaInicio->toDateString(), $fechaFin->toDateString()])
            ->groupBy('fecha_envio', 'sucursal_origen_id')
            ->orderBy('fecha_envio')
            ->get()
            ->groupBy(fn ($row) => $row->fecha_envio);

        $recibos = DB::table('giros')
            ->select('fecha_envio', 'sucursal_destino_id', DB::raw('SUM(monto_enviado) as total'))
            ->whereBetween('fecha_envio', [$fechaInicio->toDateString(), $fechaFin->toDateString()])
            ->groupBy('fecha_envio', 'sucursal_destino_id')
            ->orderBy('fecha_envio')
            ->get()
            ->groupBy(fn ($row) => $row->fecha_envio);

        $movimientos = [];
        $ahora = now()->toDateTimeString();
        $periodo = CarbonPeriod::create($fechaInicio, $fechaFin);

        foreach ($periodo as $fecha) {
            if ($fecha->isSunday()) {
                continue;
            }

            $fechaTexto = $fecha->toDateString();
            $enviosDia = collect($envios->get($fechaTexto, collect()))->keyBy('sucursal_origen_id');
            $recibosDia = collect($recibos->get($fechaTexto, collect()))->keyBy('sucursal_destino_id');

            foreach ($this->sucursalIds as $sucursalId) {
                $totalEnviado = (float) ($enviosDia->get($sucursalId)?->total ?? 0);
                $totalRecibido = (float) ($recibosDia->get($sucursalId)?->total ?? 0);

                if ($totalEnviado == 0.0 && $totalRecibido == 0.0) {
                    continue;
                }

                $capitalInicial = $capitales[$sucursalId];
                $capitalActual = max(
                    (float) self::CAPITAL_MINIMO_FINAL,
                    $capitalInicial + $totalRecibido - $totalEnviado
                );
                $capitales[$sucursalId] = $capitalActual;

                $movimientos[] = [
                    'fecha' => $fechaTexto,
                    'sucursal_id' => $sucursalId,
                    'total_enviado' => $totalEnviado,
                    'total_recibido' => $totalRecibido,
                    'balance_dia' => $totalRecibido - $totalEnviado,
                    'capital_inicial' => $capitalInicial,
                    'capital_actual' => $capitalActual,
                    'created_at' => $ahora,
                    'updated_at' => $ahora,
                    'deleted_at' => null,
                ];
            }
        }

        foreach (array_chunk($movimientos, 500) as $chunk) {
            DB::table('movimiento_capital')->insert($chunk);
        }

        $this->garantizarCapitalesPositivos($capitales);
        $this->sincronizarUltimoMovimientoCapital($capitales);

        foreach ($capitales as $sucursalId => $capital) {
            Sucursales::query()->whereKey($sucursalId)->update(['capital_actual' => round($capital, 2)]);
        }
    }

    /**
     * @param  array<int, float>  $capitales
     */
    private function garantizarCapitalesPositivos(array &$capitales): void
    {
        foreach ($capitales as $sucursalId => $capital) {
            if ($capital < self::CAPITAL_MINIMO_FINAL) {
                $capitales[$sucursalId] = (float) self::CAPITAL_MINIMO_FINAL;
            }
        }
    }

    /**
     * @param  array<int, float>  $capitales
     */
    private function sincronizarUltimoMovimientoCapital(array $capitales): void
    {
        foreach ($capitales as $sucursalId => $capital) {
            $ultimoMovimientoId = DB::table('movimiento_capital')
                ->where('sucursal_id', $sucursalId)
                ->orderByDesc('fecha')
                ->orderByDesc('id')
                ->value('id');

            if ($ultimoMovimientoId === null) {
                continue;
            }

            DB::table('movimiento_capital')
                ->where('id', $ultimoMovimientoId)
                ->update(['capital_actual' => round($capital, 2)]);
        }
    }
}
