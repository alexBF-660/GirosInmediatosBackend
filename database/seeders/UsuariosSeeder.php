<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsuariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Crear usuarios de ejemplo
        $admin = User::create([
            'name' => 'Alex Gary',
            'ap_paterno' => 'Baptista',
            'ap_materno' => 'Fariñas',
            'ci' => '6102438',
            'celular' => '67852701',
            'genero' => 'M',
            'fecha_nacimiento' => '2000-06-06',
            'email' => 'alexbaptista.abf660@gmail.com',
            'password' => Hash::make('alex.abf660'),
            'sucursal_id' => null,
        ]);
    }
}
