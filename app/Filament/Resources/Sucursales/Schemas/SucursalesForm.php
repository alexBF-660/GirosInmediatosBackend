<?php

namespace App\Filament\Resources\Sucursales\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class SucursalesForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                    ->placeholder('Nombre de la sucursal')
                    ->required(),
                Textarea::make('descripcion')
                    ->label('Descripción')
                    ->placeholder('Descripción de la sucursal')
                    ->columnSpanFull(),
                TextInput::make('direccion')
                    ->label('Dirección')
                    ->placeholder('Dirección de la sucursal'),
                TextInput::make('telefono')
                    ->label('Teléfono')
                    ->placeholder('Teléfono de la sucursal')
                    ->tel(),
                TextInput::make('capital_actual')
                    ->placeholder('Capital de la sucursal')
                    ->required()
                    ->numeric()
                    ->suffix('Bs'),
                Select::make('departamento_id')
                    ->placeholder('Seleccione el departamento')
                    ->label('Departamento')
                    ->searchable()
                    ->preload()
                    ->relationship('departamento', 'nombre')
                    ->required()
            ]);
    }
}
