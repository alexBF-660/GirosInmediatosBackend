<?php

namespace App\Filament\Resources\DistribucionCapitals\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class DistribucionCapitalForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('sucursal_origne_id')
                    ->label('Sucursal Origen')
                    ->relationship('sucursalOrigen', 'nombre')
                    ->searchable()
                    ->preload()
                    ->reactive()
                    ->required()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $sucursal = \App\Models\Sucursales::find($state);
                        $set('capital_origen', $sucursal?->capital_actual ?? 0);
                    }),

                TextInput::make('capital_origen')
                    ->label('Capital Origen')
                    ->disabled()
                    ->numeric()
                    ->default(0),

                Select::make('sucursal_destino_id')
                    ->label('Sucursal Destino')
                    ->relationship('sucursalDestino', 'nombre')
                    ->searchable()
                    ->preload()
                    ->reactive()
                    ->required()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $sucursal = \App\Models\Sucursales::find($state);
                        $set('capital_destino', $sucursal?->capital_actual ?? 0);
                    }),

                TextInput::make('capital_destino')
                    ->label('Capital Destino')
                    ->disabled()
                    ->numeric()
                    ->default(0),

                TextInput::make('monto')
                    ->required()
                    ->numeric()
                    ->default(0),
                DatePicker::make('fecha'),
                Select::make('tipo')
                    ->label('Tipo')
                    ->options([
                        'regular' => 'Distribución Regular',
                        'redistribucion' => 'Redistribución',
                    ])
                    ->required(),
                TextInput::make('observacion'),
            ]);
    }
}
