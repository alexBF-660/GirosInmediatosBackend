<?php

namespace App\Models;
use Illuminate\Support\Facades\DB;

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
        return $this->belongsTo(User::class, 'usuario_envio_id');
    }

    public function usuarioEntrega()
    {
        return $this->belongsTo(User::class, 'usuario_entrega_id');
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
            
            $ENTREGADO_ID = 1;
            $NULO_ID = 3;
            $REEMITIDO_ID = 4;
            $REVERTIDO_ID = 5;

            $originalEstado = $giro->getOriginal('estado_id');

            if ($originalEstado != $ENTREGADO_ID && $giro->estado_id == $ENTREGADO_ID) {
                $giro->sucursalDestino->capital_actual -= $giro->monto_enviado;
                $giro->sucursalDestino->save();
            }

            if($originalEstado != $NULO_ID && $giro->estado_id == $NULO_ID){
                //revertir sucursal origen
                $giro->sucursalOrigen->capital_actual += $giro->monto_enviado;
                $giro->sucursalOrigen->save();

                //revertir sucursal destino
                $giro->sucursalDestino->capital_actual -= $giro->monto_enviado;
                $giro->sucursalDestino->save();
            }

            if($originalEstado != $REEMITIDO_ID && $giro->estado_id == $REEMITIDO_ID){

                //restaurar monto de sucursal actual
                $montoOriginal = $giro->getOriginal('monto_enviado');
                $sucursalDestinoID = $giro->getOriginal('sucursal_destino_id');
                $sucursalDestinoActual = \App\Models\Sucursales::find($sucursalDestinoID);

                $sucursalDestinoActual->capital_actual -= $montoOriginal;
                $sucursalDestinoActual->save();

                //reenviar a la nueva sucursal
                $giro->sucursalDestino->capital_actual += $giro->monto_enviado;
                $giro->sucursalDestino->save();
            }

            if($originalEstado != $REVERTIDO_ID && $giro->estado_id == $REVERTIDO_ID){
                //revertir sucursal origen
                $montoOriginal = $giro->getOriginal('monto_enviado');
                $giro->sucursalOrigen->capital_actual += $giro->monto_enviado;
                $giro->sucursalOrigen->save();

                //revertir sucursal destino
                $giro->sucursalDestino->capital_actual -= $giro->montoOriginal;
                $giro->sucursalDestino->save();
            }
        });
    }
}
