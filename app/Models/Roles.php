<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
    ];
    
    // Relación con usuarios
    public function usuarios()
    {
        return $this->hasMany(Usuarios::class, 'rol_id');
    }
}
