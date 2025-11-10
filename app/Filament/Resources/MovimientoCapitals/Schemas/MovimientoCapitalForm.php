<?php

namespace App\Filament\Resources\MovimientoCapitals\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MovimientoCapitalForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('fecha'),
                TextInput::make('sucursal_id')
                    ->numeric(),
                TextInput::make('total_enviado')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('total_recibido')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('balance_dia')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('capital_inicial')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('capital_actual')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
