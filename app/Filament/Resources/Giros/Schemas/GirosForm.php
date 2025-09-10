<?php

namespace App\Filament\Resources\Giros\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\label;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;

class GirosForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre_remitente')
                    ->label('Remitente')
                    ->placeholder('Nombre de la persona que esta enviando el giro')
                    ->required(),

                TextInput::make('nombre_consignatario')
                    ->label('Consignatario')
                    ->placeholder('Nombre de la persona que recibira el giro')
                    ->required(),

                TextInput::make('monto_enviado')
                    ->label('Monto')
                    ->required()
                    ->numeric()
                    ->maxValue(99999)
                    ->minValue(0)
                    ->suffix('Bs.')
                    ->placeholder('Ej: 100.00')
                    ->rule('regex:/^\d+(\.\d{1,2})?$/'),

                TextInput::make('comision_envio')
                    ->label('Comision')
                    ->placeholder('El 4% del monto enviado')
                    ->required()
                    ->numeric()
                    ->maxValue(99999)
                    ->minValue(0)
                    ->suffix('Bs.')
                    ->rule('regex:/^\d+(\.\d{1,2})?$/'),

                DatePicker::make('fecha_envio')
                    ->label('Fecha de Envio'),

                DatePicker::make('fecha_entrega')
                    ->label('Fecha de Entrega'),

                TextInput::make('ci_consignatario')
                    ->label('Carnet del Consignatario')
                    ->placeholder('Ej: 123456789 LP'),

                Select::make('sucursal_origen_id')
                    ->label('Sucursal Origen')
                    ->searchable()
                    ->preload()
                    ->relationship('sucursalOrigen', 'nombre')
                    ->required(),

                Select::make('sucursal_destino_id')
                    ->label('Sucursal Destino')
                    ->searchable()
                    ->preload()
                    ->relationship('sucursalDestino', 'nombre')
                    ->required(),

                Select::make('usuario_envio_id')
                    ->label('Enviado por')
                    ->searchable()
                    ->preload()
                    ->relationship('usuarioEnvio', 'name')
                    ->required(),

                Select::make('usuario_entrega_id')
                    ->label('Entregado por')
                    ->searchable()
                    ->preload()
                    ->relationship('usuarioEntrega', 'name')
                    ->required(),

                Select::make('estado_id')
                    ->label('Estado del giro')
                    ->searchable()
                    ->preload()
                    ->relationship('estado', 'nombre')
                    ->required(),
            ]);
    }
}
