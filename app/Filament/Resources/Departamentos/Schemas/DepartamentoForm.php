<?php

namespace App\Filament\Resources\Departamentos\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class DepartamentoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                    ->required(),

                Select::make('pais_id')
                    ->label('Pais')
                    ->searchable()
                    ->preload()
                    ->relationship('paises', 'nombre')
                    ->required()
            ]);
    }
}
