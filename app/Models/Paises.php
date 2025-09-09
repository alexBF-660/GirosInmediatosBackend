<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paises extends Model
{
    protected $fillable = [
        'nombre',
    ];

    public function departamentos()
    {
        return $this->hasMany(Departamento::class, 'pais_id');
    }
}
