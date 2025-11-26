<?php

namespace App\Filament\Resources\MovimientosAdministrativos\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MovimientosAdministrativosForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                
                Section::make('Capital actual de todas las sucursales')
                    ->columnSpanFull()
                    ->schema([
                        Placeholder::make('sucursales_capitales_grafico_vertical')
                            ->label('Capital Actual por Sucursal')
                            ->content(function () {
                                $sucursales = \App\Models\Sucursales::select('nombre', 'capital_actual')->get();

                                if ($sucursales->isEmpty()) {
                                    return '<p>No hay sucursales registradas.</p>';
                                }

                                $maxCapital = $sucursales->max('capital_actual');
                                $containerHeight = 200; 

                                $html = '<div style="display:flex; align-items:flex-end; gap:16px; height:'.$containerHeight.'px; padding:8px;">';

                                foreach ($sucursales as $s) {
                                    $heightPx = ($s->capital_actual / $maxCapital) * $containerHeight;

                                    $color = '#3b82f6'; // azul por defecto

                                    if ($s->capital_actual > 8000) {
                                        $color = '#3b82f6'; // azul
                                    } elseif ($s->capital_actual > 5000) {
                                        $color = '#facc15'; // amarillo
                                    } elseif ($s->capital_actual > 1000) {
                                        $color = '#fb923c'; // naranja
                                    } else {
                                        $color = '#ef4444'; // rojo
                                    }
                                    $html .= "
                                        <div style='display:flex; flex-direction:column; align-items:center; justify-content:flex-end;'>
                                            <div style='width:40px; height:{$heightPx}px; background:{$color}; border-radius:4px; transition: height 0.5s;'></div>
                                            <span style='margin-top:4px; text-align:center; font-size:12px;'>{$s->nombre}</span>
                                            <span style='font-size:11px;'>{$s->capital_actual} Bs</span>
                                        </div>
                                    ";
                                }

                                $html .= '</div>';

                                return $html;
                            })
                        ->html(),
                    ]),

                Select::make('sucursal_id')
                    ->label('Sucursal')
                    ->relationship('sucursal', 'nombre')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('tipo')
                    ->label('Tipo de Movimiento')
                    ->options([
                        1 => 'Ingreso',
                        2 => 'Retiro'
                    ])
                    ->required(),
                TextInput::make('descripcion')
                    ->required(),
                TextInput::make('monto')
                    ->required()
                    ->numeric()
                    ->suffix('Bs.')
                    ->default(0),
                DatePicker::make('fecha'),

            ]);
    }
}
