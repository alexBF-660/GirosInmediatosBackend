<?php

namespace App\Models;

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
}
