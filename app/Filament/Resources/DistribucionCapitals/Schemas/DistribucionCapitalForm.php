<?php

namespace App\Filament\Resources\DistribucionCapitals\Schemas;

use App\Models\Sucursales;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Http;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;


class DistribucionCapitalForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Capital actual de todas las sucursales')
                    ->columns(1)
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



                Section::make('Información de Sucursal')
                    ->columns(1)
                    ->schema([
                        Select::make('sucursal_destino_id')
                            ->label('Sucursal destino')
                            ->relationship('sucursalDestino', 'nombre')
                            ->searchable()
                            ->preload()
                            ->reactive()
                            ->lazy()
                            ->required()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if (!$state) {
                                    $set('capital_destino', 0);
                                    $set('predicciones', null);
                                    return;
                                }

                                $sucursal = \App\Models\Sucursales::find($state);
                                $set('capital_destino', $sucursal?->capital_actual ?? 0);

                                // Cargar dataset completo
                                $data = [
                                ["fecha" => "2025-10-01", "total_enviado" => 2500, "total_recibido" => 3000, "balance_dia" => 500, "capital_inicial" => 10000, "capital_actual" => 10500],
                                        ["fecha" => "2025-10-02", "total_enviado" => 1800, "total_recibido" => 2500, "balance_dia" => 700, "capital_inicial" => 10500, "capital_actual" => 11200],
                                        ["fecha" => "2025-10-03", "total_enviado" => 3000, "total_recibido" => 3200, "balance_dia" => 200, "capital_inicial" => 11200, "capital_actual" => 11400],
                                        ["fecha" => "2025-10-06", "total_enviado" => 2200, "total_recibido" => 2800, "balance_dia" => 600, "capital_inicial" => 11400, "capital_actual" => 12000],
                                        ["fecha" => "2025-10-07", "total_enviado" => 2600, "total_recibido" => 2900, "balance_dia" => 300, "capital_inicial" => 12000, "capital_actual" => 12300],
                                        ["fecha" => "2025-10-08", "total_enviado" => 2800, "total_recibido" => 3100, "balance_dia" => 300, "capital_inicial" => 12300, "capital_actual" => 12600],
                                        ["fecha" => "2025-10-09", "total_enviado" => 2000, "total_recibido" => 2500, "balance_dia" => 500, "capital_inicial" => 12600, "capital_actual" => 13100],
                                        ["fecha" => "2025-10-10", "total_enviado" => 2200, "total_recibido" => 2600, "balance_dia" => 400, "capital_inicial" => 13100, "capital_actual" => 13500],
                                        ["fecha" => "2025-10-13", "total_enviado" => 1900, "total_recibido" => 2400, "balance_dia" => 500, "capital_inicial" => 13500, "capital_actual" => 14000],
                                        ["fecha" => "2025-10-14", "total_enviado" => 2100, "total_recibido" => 2800, "balance_dia" => 700, "capital_inicial" => 14000, "capital_actual" => 14700],
                                        ["fecha" => "2025-10-15", "total_enviado" => 2400, "total_recibido" => 3000, "balance_dia" => 600, "capital_inicial" => 14700, "capital_actual" => 15300],
                                        ["fecha" => "2025-10-16", "total_enviado" => 2500, "total_recibido" => 3200, "balance_dia" => 700, "capital_inicial" => 15300, "capital_actual" => 16000],
                                        ["fecha" => "2025-10-17", "total_enviado" => 2300, "total_recibido" => 2900, "balance_dia" => 600, "capital_inicial" => 16000, "capital_actual" => 16600],
                                        ["fecha" => "2025-10-20", "total_enviado" => 2700, "total_recibido" => 3500, "balance_dia" => 800, "capital_inicial" => 16600, "capital_actual" => 17400],
                                        ["fecha" => "2025-10-21", "total_enviado" => 3000, "total_recibido" => 3700, "balance_dia" => 700, "capital_inicial" => 17400, "capital_actual" => 18100],
                                        ["fecha" => "2025-10-22", "total_enviado" => 2600, "total_recibido" => 3100, "balance_dia" => 500, "capital_inicial" => 18100, "capital_actual" => 18600],
                                        ["fecha" => "2025-10-23", "total_enviado" => 2800, "total_recibido" => 3400, "balance_dia" => 600, "capital_inicial" => 18600, "capital_actual" => 19200],
                                        ["fecha" => "2025-10-24", "total_enviado" => 3100, "total_recibido" => 3600, "balance_dia" => 500, "capital_inicial" => 19200, "capital_actual" => 19700],
                                        ["fecha" => "2025-10-27", "total_enviado" => 2900, "total_recibido" => 3300, "balance_dia" => 400, "capital_inicial" => 19700, "capital_actual" => 20100],
                                        ["fecha" => "2025-10-28", "total_enviado" => 2700, "total_recibido" => 3100, "balance_dia" => 400, "capital_inicial" => 20100, "capital_actual" => 20500],
                                        ["fecha" => "2025-10-29", "total_enviado" => 2600, "total_recibido" => 3200, "balance_dia" => 600, "capital_inicial" => 20500, "capital_actual" => 21100],
                                        ["fecha" => "2025-10-30", "total_enviado" => 2800, "total_recibido" => 3400, "balance_dia" => 600, "capital_inicial" => 21100, "capital_actual" => 21700],
                                        ["fecha" => "2025-10-31", "total_enviado" => 3000, "total_recibido" => 3600, "balance_dia" => 600, "capital_inicial" => 21700, "capital_actual" => 22300],
                                        ["fecha" => "2025-11-03", "total_enviado" => 3100, "total_recibido" => 3800, "balance_dia" => 700, "capital_inicial" => 22300, "capital_actual" => 23000],
                                        ["fecha" => "2025-11-04", "total_enviado" => 2800, "total_recibido" => 3300, "balance_dia" => 500, "capital_inicial" => 23000, "capital_actual" => 23500],
                                        ["fecha" => "2025-11-05", "total_enviado" => 3000, "total_recibido" => 3700, "balance_dia" => 700, "capital_inicial" => 23500, "capital_actual" => 24200],
                                        ["fecha" => "2025-11-06", "total_enviado" => 2700, "total_recibido" => 3100, "balance_dia" => 400, "capital_inicial" => 24200, "capital_actual" => 24600],
                                        ["fecha" => "2025-11-07", "total_enviado" => 2600, "total_recibido" => 3000, "balance_dia" => 400, "capital_inicial" => 24600, "capital_actual" => 25000],
                                        ["fecha" => "2025-11-10", "total_enviado" => 2800, "total_recibido" => 3300, "balance_dia" => 500, "capital_inicial" => 25000, "capital_actual" => 25500],
                                        ["fecha" => "2025-11-11", "total_enviado" => 3000, "total_recibido" => 3500, "balance_dia" => 500, "capital_inicial" => 25500, "capital_actual" => 26000],
                                        ["fecha" => "2025-11-12", "total_enviado" => 2900, "total_recibido" => 3400, "balance_dia" => 500, "capital_inicial" => 26000, "capital_actual" => 26500],
                                        ["fecha" => "2025-11-13", "total_enviado" => 2700, "total_recibido" => 3200, "balance_dia" => 500, "capital_inicial" => 26500, "capital_actual" => 27000],
                                        ["fecha" => "2025-11-14", "total_enviado" => 2500, "total_recibido" => 3000, "balance_dia" => 500, "capital_inicial" => 27000, "capital_actual" => 27500],
                                        ["fecha" => "2025-11-17", "total_enviado" => 2800, "total_recibido" => 3300, "balance_dia" => 500, "capital_inicial" => 27500, "capital_actual" => 28000],
                                        ["fecha" => "2025-11-18", "total_enviado" => 2800, "total_recibido" => 3300, "balance_dia" => 500, "capital_inicial" => 25000, "capital_actual" => 25500],
                                ];

                                $payload = [
                                    'sucursal_id' => $state,
                                    'data' => $data,
                                ];

                                // Enviar POST a la API
                                rescue(function () use ($payload, $set) {
                                    $response = \Illuminate\Support\Facades\Http::timeout(5)
                                        ->post('http://127.0.0.1:5000/predict', $payload);

                                    if ($response->successful()) {
                                        $set('predicciones', $response->json('predicciones'));
                                    } else {
                                        $set('predicciones', [['fecha' => '-', 'prediccion_capital' => 'Error en la predicción']]);
                                    }
                                }, function () use ($set) {
                                    $set('predicciones', [['fecha' => '-', 'prediccion_capital' => 'Error de conexión']]);
                                });
                            }),

                        TextInput::make('capital_destino')
                            ->label('Capital actual')
                            ->disabled()
                            ->numeric()
                            ->default(0),
                    ]),

                Section::make('Prediccion de Capital y Detalles de Distribución')
                    ->columns(1)
                    ->schema([
                        Placeholder::make('predicciones_box')
                    ->label('Predicciones de Capital para los próximos 7 días')
                    ->content(function ($get) {
                        $predicciones = $get('predicciones');

                        // Formatear el contenido como HTML
                        return collect($predicciones)
                            ->map(
                                fn ($item) => 
                                "
                                <div>
                                    <strong>Fecha</strong>: {$item['fecha']} <br> 
                                    <strong>Capital estimado</strong>: " . number_format($item['prediccion_capital'], 2) . " Bs
                                </div><br>
                                "
                            )
                            ->implode('');
                    })
                    ->html() // <-- importante, habilita interpretación de HTML
                    ->visible(fn ($get) => filled($get('predicciones'))),
                    ]),

                section::make('Información de Transferencia')
                    ->columns(2)             // Fuerza 1 columna interna
                    ->columnSpanFull()
                    ->schema([
                        Select::make('sucursal_origen_id')
                            ->label('Sucursal Origen')
                            ->relationship('sucursalOrigen', 'nombre')
                            ->searchable()
                            ->preload()
                            ->reactive()
                            ->lazy()
                            ->required()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $sucursal = Sucursales::find($state);
                                $set('capital_origen', $sucursal?->capital_actual ?? 0);
                            }),
        
                        TextInput::make('capital_origen')
                            ->label('Capital Actual')
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
                                '1' => 'Distribución Regular',
                                '2' => 'Redistribución',
                            ])
                            ->required(),
        
                        TextInput::make('observacion'),
                    ]),

            ]);
    }
}
