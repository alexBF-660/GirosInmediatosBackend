<?php

namespace App\Filament\Resources\Giros\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class GirosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('numero')
                    ->label('N°')
                    ->rowIndex(),
                TextColumn::make('id')
                    ->label('N° Guia')
                    ->searchable(),

                TextColumn::make('nombre_remitente')
                    ->label('Remitente')
                    ->searchable(),

                TextColumn::make('nombre_consignatario')
                    ->label('Consignatario')
                    ->searchable(),

                TextColumn::make('monto_enviado')
                    ->label('Monto')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('comision_envio')
                    ->label('Comision')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('fecha_envio')
                    ->date()
                    ->sortable(),

                TextColumn::make('fecha_entrega')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('ci_consignatario')
                    ->label('Carnet Consignatario')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('sucursalOrigen.nombre')
                    ->label('Sucursal Origen')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('sucursalDestino.nombre')
                    ->label('Sucursal Destino')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('usuarioEnvio.name')
                    ->label('Enviado por')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('usuarioEntrega.name')
                    ->label('Entregado por')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('estado.nombre')
                    ->label('Estado')
                    ->sortable()
                    ->colors([
                        'success' => 'Entregado',
                        'warning' => 'Pendiente',
                        'secondary' => 'Nulo',
                    ]),

                TextColumn::make('created_at')
                    ->label('Fecha de creacion')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Fecha de modificacion')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                TextColumn::make('deleted_at')
                    ->label('Fecha de eliminacion')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('sucursal_origen_id')
                    ->label('Enviados desde')
                    ->relationship('sucursalOrigen', 'nombre'),
                SelectFilter::make('sucursal_destino_id')
                    ->label('Recibidos en')
                    ->relationship('sucursalDestino', 'nombre'),
                SelectFilter::make('estado_id')
                    ->label('Estado')
                    ->relationship('estado', 'nombre'),
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
