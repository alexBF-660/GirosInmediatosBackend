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
                    ->required(),
                Textarea::make('descripcion')
                    ->columnSpanFull(),
                TextInput::make('direccion'),
                TextInput::make('telefono')
                    ->tel(),
                TextInput::make('capital_actual')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->suffix('Bs'),
                Select::make('departamento_id')
                    ->label('Departamento')
                    ->searchable()
                    ->preload()
                    ->relationship('departamento', 'nombre')
                    ->required()
            ]);
    }
}
