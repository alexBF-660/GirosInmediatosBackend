<?php

namespace App\Models;

use App\Models\MovimientoCapital;
use Illuminate\Database\Eloquent\Model;

class MovimientosAdministrativos extends Model
{
    protected $table = 'movimientos_administrativos';

    protected $fillable = [
        'sucursal_id',
        'monto',
        'fecha',
        'tipo',
        'descripcion',
    ];

    public function sucursal()
    {
        return $this->belongsTo(Sucursales::class, 'sucursal_id');
    }


    protected static function booted()
    {
        static::created(function ($movimiento) {

            // =============================================
            // ACTUALIZAR CAPITAL EN SUCURSALES
            // =============================================

            if ($movimiento->tipo == 1) { //Ingreso
                $movimiento->sucursal->capital_actual += $movimiento->monto;
                $movimiento->sucursal->save();
            }

            if ($movimiento->tipo == 2) { //Retiro
                $movimiento->sucursal->capital_actual -= $movimiento->monto;
                $movimiento->sucursal->save();
            }

        });
    }
}
