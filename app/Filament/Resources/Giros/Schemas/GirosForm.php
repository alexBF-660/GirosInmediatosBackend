<?php

namespace App\Filament\Resources\Giros\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\label;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Carbon\Carbon;
use Filament\Facades\Filament;
use App\Models\User;

class GirosForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre_remitente')
                    ->label('Remitente')
                    ->placeholder('Nombre de la persona que esta enviando el giro')
                    ->disabled(fn ($state, $record) => $record && !Filament::auth()->user()->hasRole('super_admin') == 1 && in_array($record->estado_id, [1, 3]))
                    ->required(),

                TextInput::make('nombre_consignatario')
                    ->label('Consignatario')
                    ->placeholder('Nombre de la persona que recibira el giro')
                    ->disabled(fn ($state, $record) => $record && !Filament::auth()->user()->hasRole('super_admin') == 1 && in_array($record->estado_id, [1, 3]))
                    ->required(),

                TextInput::make('monto_enviado')
                    ->label('Monto')
                    ->required()
                    ->numeric()
                    ->maxValue(99999)
                    ->minValue(0)
                    ->suffix('Bs.')
                    ->disabled(fn ($state, $record) => $record && !Filament::auth()->user()->hasRole('super_admin') == 1 && in_array($record->estado_id, [1, 3]))
                    ->placeholder('Ej: 100.00')
                    ->rule('regex:/^\d+(\.\d{1,2})?$/'),

                TextInput::make('comision_envio')
                    ->label('Comision')
                    ->placeholder('El comision por el monto enviado.')
                    ->required()
                    ->numeric()
                    ->maxValue(99999)
                    ->minValue(0)
                    ->suffix('Bs.')
                    ->disabled(fn ($state, $record) => $record && !Filament::auth()->user()->hasRole('super_admin') == 1 && in_array($record->estado_id, [1, 3]))
                    ->rule('regex:/^\d+(\.\d{1,2})?$/'),

                DatePicker::make('fecha_envio')
                    ->label('Fecha de Envio')
                    ->default(now())
                    ->disabled(fn ($state, $record) => $record && !Filament::auth()->user()->hasRole('super_admin') == 1 && in_array($record->estado_id, [1, 3]))
                    ->required(),

                DatePicker::make('fecha_entrega')
                    ->disabled(fn ($state, $record) => $record && !Filament::auth()->user()->hasRole('super_admin') == 1 && in_array($record->estado_id, [1, 3]))
                    ->label('Fecha de Entrega'),

                TextInput::make('ci_consignatario')
                    ->label('Carnet del Consignatario')
                    ->disabled(fn ($state, $record) => $record && !Filament::auth()->user()->hasRole('super_admin') == 1 && in_array($record->estado_id, [1, 3]))
                    ->placeholder('Ej: 123456789 LP'),

                Select::make('sucursal_origen_id')
                    ->label('Sucursal Origen')
                    ->relationship('sucursalOrigen', 'nombre')
                    ->default(fn () => Filament::auth()->user()?->sucursal_id)
                    ->disabled(fn ($state, $record) => $record && !Filament::auth()->user()->hasRole('super_admin') == 1 && in_array($record->estado_id, [1, 3]))
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('sucursal_destino_id')
                    ->label('Sucursal Destino')
                    ->relationship('sucursalDestino', 'nombre')
                    ->searchable()
                    ->preload()
                    ->disabled(fn ($state, $record) => $record && !Filament::auth()->user()->hasRole('super_admin') == 1 && in_array($record->estado_id, [1, 3]))
                    ->required(),

                Select::make('usuario_envio_id')
                    ->label('Enviado por')
                    ->relationship('usuarioEnvio', 'name')
                    ->default(fn () => Filament::auth()->user()?->id)
                    ->disabled(fn ($state, $record) => $record && !Filament::auth()->user()->hasRole('super_admin') == 1 && in_array($record->estado_id, [1, 3]))
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('usuario_entrega_id')
                    ->label('Entregado por')
                    ->searchable()
                    ->preload()
                    ->disabled(fn ($state, $record) => $record && !Filament::auth()->user()->hasRole('super_admin') == 1 && in_array($record->estado_id, [1, 3]))
                    ->options(function (callable $get) {
                        $sucursalDestinoId = $get('sucursal_destino_id');
                        if (! $sucursalDestinoId) {
                            return [];
                        }
                        return User::where('sucursal_id', $sucursalDestinoId)
                            ->pluck('name', 'id');
                    }),

                Select::make('estado_id')
                    ->label('Estado del giro')
                    ->relationship('estado', 'nombre')
                    ->default(2)
                    ->searchable()
                    ->preload()
                    ->disabled(fn ($state, $record) => $record && !Filament::auth()->user()->hasRole('super_admin') == 1 && in_array($record->estado_id, [1, 3]))
                    ->required(),
            ]);
    }
}
