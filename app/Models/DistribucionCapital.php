<?php

namespace App\Models;

use App\Models\MovimientoCapital;
use Illuminate\Database\Eloquent\Model;

class DistribucionCapital extends Model
{
    protected $table = 'distribucion_capital';

    protected $fillable = [
        'sucursal_origen_id',
        'sucursal_destino_id',
        'monto',
        'fecha',
        'tipo',
        'observacion',
    ];

    public function sucursalOrigen()
    {
        return $this->belongsTo(Sucursales::class, 'sucursal_origen_id');
    }

    public function sucursalDestino()
    {
        return $this->belongsTo(Sucursales::class, 'sucursal_destino_id');
    }

    protected static function booted()
    {
        static::created(function ($distribucion) {

            // =============================================
            // ACTUALIZAR CAPITAL EN SUCURSALES
            // =============================================

            if ($distribucion->sucursalOrigen) {
                $distribucion->sucursalOrigen->capital_actual -= $distribucion->monto;
                $distribucion->sucursalOrigen->save();
            }

            if ($distribucion->sucursalDestino) {
                $distribucion->sucursalDestino->capital_actual += $distribucion->monto;
                $distribucion->sucursalDestino->save();
            }


            // =============================================
            // MOVIMIENTO CAPITAL - ORIGEN
            // =============================================
            $movOrigen = MovimientoCapital::whereDate('fecha', today())
                ->where('sucursal_id', $distribucion->sucursal_origen_id)
                ->first();

            if (!$movOrigen) {

                $ultimo = MovimientoCapital::where('sucursal_id', $distribucion->sucursal_origen_id)
                    ->orderBy('fecha', 'desc')
                    ->first();

                $capitalAnterior = $ultimo?->capital_actual ?? 0;

                // capital_inicial se mantiene igual al del día anterior
                $movOrigen = MovimientoCapital::create([
                    'fecha'           => today(),
                    'sucursal_id'     => $distribucion->sucursal_origen_id,
                    'total_enviado'   => 0,
                    'total_recibido'  => 0,
                    'balance_dia'     => 0,
                    'capital_inicial' => $capitalAnterior,
                    'capital_actual'  => $capitalAnterior - $distribucion->monto,
                ]);

            } else {
                $movOrigen->capital_actual -= $distribucion->monto;
                $movOrigen->save();
            }


            // =============================================
            // MOVIMIENTO CAPITAL - DESTINO
            // =============================================
            $movDestino = MovimientoCapital::whereDate('fecha', today())
                ->where('sucursal_id', $distribucion->sucursal_destino_id)
                ->first();

            if (!$movDestino) {

                $ultimo = MovimientoCapital::where('sucursal_id', $distribucion->sucursal_destino_id)
                    ->orderBy('fecha', 'desc')
                    ->first();

                $capitalAnterior = $ultimo?->capital_actual ?? 0;

                $movDestino = MovimientoCapital::create([
                    'fecha'           => today(),
                    'sucursal_id'     => $distribucion->sucursal_destino_id,
                    'total_enviado'   => 0,
                    'total_recibido'  => 0,
                    'balance_dia'     => 0,
                    'capital_inicial' => $capitalAnterior,
                    'capital_actual'  => $capitalAnterior + $distribucion->monto,
                ]);

            } else {
                $movDestino->capital_actual += $distribucion->monto;
                $movDestino->save();
            }
        });
    }
}
