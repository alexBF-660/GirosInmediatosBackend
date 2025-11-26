<?php

namespace App\Filament\Resources\MovimientosAdministrativos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MovimientosAdministrativosTable
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
                TextColumn::make('tipo')
                    ->label('Tipo')
                    ->formatStateUsing(function ($state) {
                        return [
                            '1' => 'Ingerso',
                            '2' => 'Retiro',
                        ][$state] ?? 'Desconocido';
                    })
                    ->sortable(),
                TextColumn::make('descripcion')
                    ->searchable(),
                TextColumn::make('monto')
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
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
