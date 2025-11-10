<?php

namespace App\Filament\Resources\MovimientoCapitals\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MovimientoCapitalsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('fecha')
                    ->date()
                    ->sortable(),
                TextColumn::make('sucursal.nombre')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_enviado')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_recibido')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('balance_dia')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('capital_inicial')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('capital_actual')
                    ->numeric()
                    ->sortable(),
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
                //EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
