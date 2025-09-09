<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sucursales extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
        'direccion',
        'telefono',
        'capital_actual',
        'departamento_id',
    ];

    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'departamento_id');
    }

    public function usuarios()
    {
        return $this->hasMany(Usuarios::class, 'sucursal_id');
    }

    public function girosOrigen()
    {
        return $this->hasMany(Giro::class, 'sucursal_origen_id');
    }

    public function girosDestino()
    {
        return $this->hasMany(Giro::class, 'sucursal_destino_id');
    }
}
