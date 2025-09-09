<?php

namespace App\Filament\Resources\Usuarios\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;

class UsuariosForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombres')
                    ->required(),
                TextInput::make('ap_paterno')
                    ->required(),
                TextInput::make('ap_materno'),
                TextInput::make('ci')
                    ->required(),
                TextInput::make('celular'),
                    Select::make('genero')
                        ->label('Género')
                        ->options([
                            'M' => 'Masculino',
                            'F' => 'Femenino',
                            'O' => 'Otro',
                        ])
                        ->required(),
                DatePicker::make('fecha_nacimiento'),
                TextInput::make('correo')
                    ->required(),
                TextInput::make('password')
                    ->password()
                    ->required(),
                Select::make('rol_id')
                    ->label('Rol')
                    ->searchable()
                    ->preload()
                    ->relationship('rol', 'nombre')
                    ->required(),
                Select::make('sucursal_id')
                    ->label('Sucursal')
                    ->searchable()
                    ->preload()
                    ->relationship('sucursal', 'nombre')
                    ->required(),
                FileUpload::make('foto')
                    ->disk('public')
                    ->label('Foto del usuario')
                    ->visibility('public')
                    ->preserveFilenames()
                    ->acceptedFileTypes(['image/*'])
                    ->required(),
            ]);
    }
}
