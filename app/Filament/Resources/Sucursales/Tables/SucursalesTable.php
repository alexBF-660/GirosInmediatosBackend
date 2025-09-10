<?php

namespace App\Filament\Resources\Sucursales\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SucursalesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre')
                    ->label('Sucursal')
                    ->searchable(),
                
                TextColumn::make('capital_actual')
                    ->label('Capital Actual')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('direccion')
                    ->searchable(),

                TextColumn::make('telefono')
                    ->searchable(),

                TextColumn::make('departamento.nombre')
                    ->label('Departamento')
                    ->numeric()
                    ->sortable(),

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
                SelectFilter::make('departamento_id')
                    ->label('Departamento')
                    ->relationship('departamento', 'nombre'),
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
