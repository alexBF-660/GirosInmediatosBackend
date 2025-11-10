<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovimientoCapital extends Model
{
    protected $table = 'movimiento_capital';
    protected $fillable = [
        'fecha',
        'sucursal_id',
        'total_enviado',
        'total_recibido',
        'balance_dia',
        'capital_inicial',
        'capital_actual'
    ];

    public function sucursal()
    {
        return $this->belongsTo(Sucursales::class, 'sucursal_id');
    }
}
