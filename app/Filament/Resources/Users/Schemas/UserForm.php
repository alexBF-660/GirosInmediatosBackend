<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nombres')
                    ->placeholder('Nombres del usuario')
                    ->required(),
                TextInput::make('ap_paterno')
                    ->placeholder('Apellido paterno del usuario')
                    ->required(),
                TextInput::make('ap_materno')
                    ->placeholder('Apellido materno del usuario'),
                TextInput::make('ci')
                    ->placeholder('Número de documento o de carnet del usuario')
                    ->required(),
                TextInput::make('celular')
                    ->placeholder('Número de celular del usuario'),
                Select::make('genero')
                    ->label('Género')
                    ->placeholder('Seleccione el género del usuario')
                    ->options([
                        'M' => 'Masculino',
                        'F' => 'Femenino',
                        'O' => 'Otro',
                    ])
                    ->required(),
                DatePicker::make('fecha_nacimiento'),
                TextInput::make('email')
                    ->label('correo')
                    ->placeholder('Correo del usuario')
                    ->email()
                    ->required(),
                TextInput::make('password')
                    ->placeholder('Contraseña para el ingreso al sistema')
                    ->password(),
                Select::make('sucursal_id')
                    ->label('Sucursal')
                    ->placeholder('Seleccione la sucursal del usuario')
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
