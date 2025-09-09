<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Giros extends Model
{    
    protected $fillable = [
        'nombre_remitente',
        'nombre_consignatario',
        'monto_enviado',
        'comision_envio',
        'fecha_envio',
        'fecha_entrega',
        'ci_consignatario',
        'sucursal_origen_id',
        'sucursal_destino_id',
        'usuario_envio_id',
        'usuario_entrega_id',
        'estado_id',
    ];

    protected $casts = [
        'fecha_envio' => 'datetime',
        'fecha_entrega' => 'datetime',
        'monto_enviado' => 'decimal:2',
        'comision_envio' => 'decimal:2',
    ];

    // Relaciones
    public function sucursalOrigen()
    {
        return $this->belongsTo(Sucursales::class, 'sucursal_origen_id');
    }

    public function sucursalDestino()
    {
        return $this->belongsTo(Sucursales::class, 'sucursal_destino_id');
    }

    public function usuarioEnvio()
    {
        return $this->belongsTo(Usuarios::class, 'usuario_envio_id');
    }

    public function usuarioEntrega()
    {
        return $this->belongsTo(Usuarios::class, 'usuario_entrega_id');
    }

    public function estado()
    {
        return $this->belongsTo(Estado_Giros::class, 'estado_id');
    }

    //hook de actualizacion del monto actual de las sucursales
    protected static function booted()
    {
        //cuando se crea un giro
        static::created(function ($giro) {
            // Descuento en la sucursal de origen
            if ($giro->sucursalOrigen) {
                $giro->sucursalOrigen->capital_actual -= $giro->monto_enviado;
                $giro->sucursalOrigen->save();
            }

            // Suma en la sucursal de destino
            if ($giro->sucursalDestino) {
                $giro->sucursalDestino->capital_actual += $giro->monto_enviado;
                $giro->sucursalDestino->save();
            }
        });

        // Cuando se actualiza un giro
        static::updated(function ($giro) {
            $originalMonto = $giro->getOriginal('monto_enviado');
            $originalOrigen = $giro->getOriginal('sucursal_origen_id');
            $originalDestino = $giro->getOriginal('sucursal_destino_id');

            // 1. Revertir el movimiento anterior
            if ($originalOrigen && $originalOrigen != $giro->sucursal_origen_id) {
                // Si cambió de sucursal origen
                $oldSucursalOrigen = \App\Models\Sucursales::find($originalOrigen);
                if ($oldSucursalOrigen) {
                    $oldSucursalOrigen->capital_actual += $originalMonto;
                    $oldSucursalOrigen->save();
                }
            } else {
                // Misma sucursal origen → devolver el monto anterior
                if ($giro->sucursalOrigen) {
                    $giro->sucursalOrigen->capital_actual += $originalMonto;
                    $giro->sucursalOrigen->save();
                }
            }

            if ($originalDestino && $originalDestino != $giro->sucursal_destino_id) {
                // Si cambió de sucursal destino
                $oldSucursalDestino = \App\Models\Sucursales::find($originalDestino);
                if ($oldSucursalDestino) {
                    $oldSucursalDestino->capital_actual -= $originalMonto;
                    $oldSucursalDestino->save();
                }
            } else {
                // Misma sucursal destino → revertir monto anterior
                if ($giro->sucursalDestino) {
                    $giro->sucursalDestino->capital_actual -= $originalMonto;
                    $giro->sucursalDestino->save();
                }
            }

            // 2. Aplicar el nuevo movimiento
            if ($giro->sucursalOrigen) {
                $giro->sucursalOrigen->capital_actual -= $giro->monto_enviado;
                $giro->sucursalOrigen->save();
            }

            if ($giro->sucursalDestino) {
                $giro->sucursalDestino->capital_actual += $giro->monto_enviado;
                $giro->sucursalDestino->save();
            }
        });
    }
}
