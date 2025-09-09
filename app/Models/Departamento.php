<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    protected $fillable = [
        'nombre',
        'pais_id',
    ];

    public function paises()
    {
        return $this->belongsTo(Paises::class, 'pais_id');
    }

    public function sucursales()
    {
        return $this->hasMany(Sucursal::class, 'departamento_id');
    }
}
