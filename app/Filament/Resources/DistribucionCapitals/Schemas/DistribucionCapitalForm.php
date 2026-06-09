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
    private const CAPITAL_SIM_MIN = 500;

    private const CAPITAL_SIM_MAX = 8000;

    protected static function capitalSimulacion(?float $valor): float
    {
        if ($valor === null) {
            return (float) self::CAPITAL_SIM_MIN;
        }

        return max(self::CAPITAL_SIM_MIN, min(self::CAPITAL_SIM_MAX, $valor));
    }

    protected static function solicitarPredicciones(?int $sucursalId, ?float $capitalBase, callable $set): void
    {
        if (! $sucursalId) {
            $set('predicciones', null);

            return;
        }

        $capitalBase = self::capitalSimulacion($capitalBase);

        $movimientos = MovimientoCapital::query()
            ->where('sucursal_id', $sucursalId)
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
            'sucursal_id' => $sucursalId,
            'data' => $data,
            'capital_base' => $capitalBase,
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
    }

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

                                $maxCapital = max($sucursales->max('capital_actual'), 1);
                                $containerHeight = 200;

                                $html = '<div style="display:flex; align-items:flex-end; gap:16px; height:'.$containerHeight.'px; padding:8px;">';

                                foreach ($sucursales as $s) {
                                    $capital = (float) $s->capital_actual;
                                    $heightPx = ($capital / $maxCapital) * $containerHeight;
                                    $capitalFmt = number_format($capital, 2, ',', '.');

                                    if ($capital > 6000) {
                                        $color = '#3b82f6';
                                    } elseif ($capital > 4000) {
                                        $color = '#facc15';
                                    } elseif ($capital > 1500) {
                                        $color = '#fb923c';
                                    } else {
                                        $color = '#ef4444';
                                    }

                                    $html .= "
                                        <div style='display:flex; flex-direction:column; align-items:center; justify-content:flex-end;'>
                                            <div style='width:40px; height:{$heightPx}px; background:{$color}; border-radius:4px; transition: height 0.5s;'></div>
                                            <span style='margin-top:4px; text-align:center; font-size:12px;'>{$s->nombre}</span>
                                            <span style='font-size:11px;'>{$capitalFmt} Bs</span>
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
                                        $capital = round((float) ($sucursal?->capital_actual ?? self::CAPITAL_SIM_MIN), 2);
                                        $set('capital_destino', $capital);
                                        self::solicitarPredicciones((int) $state, $capital, $set);
                                    }),

                                TextInput::make('capital_destino')
                                    ->label('Capital de referencia')
                                    ->numeric()
                                    ->minValue(self::CAPITAL_SIM_MIN)
                                    ->maxValue(self::CAPITAL_SIM_MAX)
                                    ->required()
                                    ->live(debounce: 600)
                                    ->afterStateUpdated(function ($state, $get, $set) {
                                        $sucursalId = $get('sucursal_destino_id');

                                        if (! $sucursalId) {
                                            return;
                                        }

                                        self::solicitarPredicciones((int) $sucursalId, (float) $state, $set);
                                    }),
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
