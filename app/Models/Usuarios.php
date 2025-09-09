<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuarios extends Authenticatable
{
    protected $fillable = [
        'nombres',
        'ap_paterno',
        'ap_materno',
        'ci',
        'celular',
        'foto',
        'genero',
        'fecha_nacimiento',
        'correo',
        'password',
        'rol_id',
        'sucursal_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'password' => 'hashed',
    ];

    // Encriptar password automáticamente
    protected function setPasswordAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['password'] = bcrypt($value);
        }
    }

    // Relaciones
    public function rol()
    {
        return $this->belongsTo(Roles::class);
    }

    public function sucursal()
    {
        return $this->belongsTo(Sucursales::class);
    }

}
