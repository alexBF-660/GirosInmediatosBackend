<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estado_Giros extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    public function giros()
    {
        return $this->hasMany(Giro::class, 'estado_id');
    }
}
