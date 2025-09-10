<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuarios extends Authenticatable
{
    protected $table = 'usuarios';

    protected $fillable = [
        'neme',
        'ap_paterno',
        'ap_materno',
        'ci',
        'celular',
        'foto',
        'genero',
        'fecha_nacimiento',
        'email',
        'password',
        'rol_id',
        'sucursal_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date'
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
