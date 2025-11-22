<?php

namespace App\Filament\Resources\DistribucionCapitals\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DistribucionCapitalsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sucursalOrigen.nombre')
                    ->label('Sucursal origen')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('sucursalDestino.nombre')
                    ->label('Sucursal destino')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('monto')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('fecha')
                    ->date()
                    ->sortable(),
                TextColumn::make('tipo')
                    ->label('Tipo')
                    ->formatStateUsing(function ($state) {
                        return [
                            '1' => 'Distribución regular',
                            '2' => 'Redistribución',
                        ][$state] ?? 'Desconocido';
                    })
                    ->sortable(),
                TextColumn::make('observacion')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
