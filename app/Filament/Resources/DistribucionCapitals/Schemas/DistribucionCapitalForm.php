<?php

namespace App\Filament\Resources\DistribucionCapitals\Schemas;

use App\Models\Sucursales;
use App\Models\MovimientoCapital;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Http;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\View;
use Filament\Forms\Components\DatePicker;


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



                Grid::make([
                    'default' => 1,
                    'lg' => 3,
                ])
                    ->columnSpanFull()
                    ->schema([
                        Section::make('Información de Sucursal')
                            ->columns(1)
                            ->columnSpan(['default' => 1, 'lg' => 1])
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

                                        $sucursal = Sucursales::find($state);
                                        $set('capital_destino', $sucursal?->capital_actual ?? 0);

                                        $movimientos = MovimientoCapital::query()
                                            ->where('sucursal_id', $state)
                                            ->orderByDesc('fecha')
                                            ->limit(180)
                                            ->get([
                                                'fecha',
                                                'total_enviado',
                                                'total_recibido',
                                                'balance_dia',
                                                'capital_inicial',
                                                'capital_actual',
                                            ])
                                            ->sortBy('fecha')
                                            ->values();

                                        if ($movimientos->count() < 35) {
                                            $set('predicciones', [[
                                                'fecha' => '-',
                                                'prediccion_capital' => 'Se necesitan al menos 35 movimientos de capital para predecir.',
                                            ]]);

                                            return;
                                        }

                                        $data = $movimientos->map(fn (MovimientoCapital $movimiento): array => [
                                            'fecha' => $movimiento->fecha->format('Y-m-d'),
                                            'total_enviado' => (float) $movimiento->total_enviado,
                                            'total_recibido' => (float) $movimiento->total_recibido,
                                            'balance_dia' => (float) $movimiento->balance_dia,
                                            'capital_inicial' => (float) $movimiento->capital_inicial,
                                            'capital_actual' => (float) $movimiento->capital_actual,
                                        ])->values()->all();

                                        $payload = [
                                            'sucursal_id' => $state,
                                            'data' => $data,
                                        ];

                                        $lstmUrl = rtrim(config('services.lstm.url'), '/') . '/predict';

                                        rescue(function () use ($payload, $set, $lstmUrl) {
                                            $response = Http::timeout(30)
                                                ->post($lstmUrl, $payload);

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
                            ->columnSpan(['default' => 1, 'lg' => 2])
                            ->schema([
                                Hidden::make('predicciones'),
                                View::make('filament.fileds.predicciones-chart')
                                    ->visible(fn ($get) => filled($get('predicciones')))
                                    ->extraAttributes(['class' => 'predicciones-chart-field']),
                            ]),
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
