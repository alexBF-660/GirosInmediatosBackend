@php
    use Illuminate\Support\Carbon;

    $items = collect($get('predicciones') ?? []);
    $values = $items->pluck('prediccion_capital')->map(fn ($v) => (float) $v);
    $count = max($items->count(), 1);

    $minY = $values->min() ?? 0;
    $maxY = $values->max() ?? 0;
    $range = max($maxY - $minY, 1);
    $minY -= $range * 0.12;
    $maxY += $range * 0.12;
    $ySpan = max($maxY - $minY, 1);

    $width = 1000;
    $height = 440;
    $padL = 108;
    $padR = 32;
    $padT = 8;
    $padB = 88;
    $chartW = $width - $padL - $padR;
    $chartH = $height - $padT - $padB;

    $points = [];
    $circles = [];

    foreach ($items as $index => $item) {
        $value = (float) ($item['prediccion_capital'] ?? 0);
        $x = $padL + ($count > 1 ? ($index / ($count - 1)) * $chartW : $chartW / 2);
        $y = $padT + $chartH - (($value - $minY) / $ySpan) * $chartH;

        $fechaRaw = $item['fecha'] ?? '-';
        $dayLabel = '-';
        $dateLabel = $fechaRaw;

        try {
            $date = Carbon::parse($fechaRaw);
            $dayLabel = ucfirst($date->locale('es')->translatedFormat('D'));
            $dateLabel = $date->format('d/m');
        } catch (\Throwable) {
            // Mantener etiquetas por defecto si la fecha no es válida.
        }

        $points[] = round($x, 1) . ',' . round($y, 1);
        $circles[] = [
            'x' => round($x, 1),
            'y' => round($y, 1),
            'value' => $value,
            'fecha' => $fechaRaw,
            'day_label' => $dayLabel,
            'date_label' => $dateLabel,
        ];
    }

    $polyline = implode(' ', $points);

    $gridLines = 5;
    $yLabels = [];
    for ($i = 0; $i <= $gridLines; $i++) {
        $value = $minY + ($ySpan / $gridLines) * $i;
        $y = $padT + $chartH - ($i / $gridLines) * $chartH;
        $yLabels[] = [
            'y' => round($y, 1),
            'label' => number_format($value, 0, '.', ',') . ' Bs',
        ];
    }
@endphp

@if ($items->isEmpty())
    <p style="opacity:0.7; font-size:13px;">Seleccione una sucursal destino para ver la predicción.</p>
@else
<style>
    .predicciones-chart-field {
        margin-top: -0.75rem;
    }

    .predicciones-chart {
        position: relative;
        width: 100%;
        min-height: 440px;
        margin-top: -0.25rem;
        overflow: visible;
    }

    .predicciones-chart svg {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        display: block;
        overflow: visible;
    }

    .predicciones-chart svg .chart-static {
        pointer-events: none;
    }

    .predicciones-chart-point {
        cursor: pointer;
        pointer-events: all;
    }

    .predicciones-chart-point .predicciones-chart-hit {
        fill: transparent;
        stroke: transparent;
    }

    .predicciones-chart-point .predicciones-chart-dot {
        transition: r 0.15s ease, stroke-width 0.15s ease;
    }

    .predicciones-chart-point:hover .predicciones-chart-dot,
    .predicciones-chart-point:focus-visible .predicciones-chart-dot {
        r: 9;
        stroke-width: 2.5;
    }

    .predicciones-chart-point .predicciones-chart-tooltip {
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.15s ease;
    }

    .predicciones-chart-point:hover .predicciones-chart-tooltip,
    .predicciones-chart-point:focus-visible .predicciones-chart-tooltip {
        opacity: 1;
    }
</style>

<div class="predicciones-chart" style="height: {{ $height }}px;">
    <svg
        viewBox="0 0 {{ $width }} {{ $height }}"
        role="img"
        aria-label="Gráfico de predicción de capital"
    >
        @foreach ($yLabels as $line)
            <line class="chart-static" x1="{{ $padL }}" y1="{{ $line['y'] }}" x2="{{ $width - $padR }}" y2="{{ $line['y'] }}" stroke="rgba(148,163,184,0.25)" stroke-width="1"/>
            <text
                class="chart-static"
                x="{{ $padL - 10 }}"
                y="{{ $line['y'] + 5 }}"
                text-anchor="end"
                fill="rgba(226,232,240,0.95)"
                font-size="15"
                font-weight="500"
            >{{ $line['label'] }}</text>
        @endforeach

        <line class="chart-static" x1="{{ $padL }}" y1="{{ $padT }}" x2="{{ $padL }}" y2="{{ $padT + $chartH }}" stroke="rgba(148,163,184,0.5)" stroke-width="1.5"/>
        <line class="chart-static" x1="{{ $padL }}" y1="{{ $padT + $chartH }}" x2="{{ $width - $padR }}" y2="{{ $padT + $chartH }}" stroke="rgba(148,163,184,0.5)" stroke-width="1.5"/>

        @if ($minY < 0 && $maxY > 0)
            @php
                $zeroY = round($padT + $chartH - ((0 - $minY) / $ySpan) * $chartH, 1);
            @endphp
            <line class="chart-static" x1="{{ $padL }}" y1="{{ $zeroY }}" x2="{{ $width - $padR }}" y2="{{ $zeroY }}" stroke="rgba(251,191,36,0.35)" stroke-width="1" stroke-dasharray="4,4"/>
        @endif

        @if (count($points) > 1)
            <polyline class="chart-static" points="{{ $polyline }}" fill="none" stroke="#f59e0b" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"/>
        @endif

        @foreach ($circles as $point)
            @php
                $tooltipText = number_format($point['value'], 2, '.', ',') . ' Bs';
                $tooltipWidth = max(strlen($tooltipText) * 9, 108);
                $tooltipHalf = $tooltipWidth / 2;
            @endphp
            <g
                class="predicciones-chart-point"
                transform="translate({{ $point['x'] }}, {{ $point['y'] }})"
                tabindex="0"
                role="button"
                aria-label="{{ $tooltipText }}"
            >
                <circle class="predicciones-chart-hit" r="32"/>
                <circle class="predicciones-chart-dot" r="6.5" fill="#f59e0b" stroke="#1f2937" stroke-width="2"/>
                <g class="predicciones-chart-tooltip" transform="translate(0, -46)">
                    <rect
                        x="{{ -$tooltipHalf }}"
                        y="-30"
                        width="{{ $tooltipWidth }}"
                        height="30"
                        rx="8"
                        fill="rgb(17 24 39)"
                        stroke="rgb(75 85 99)"
                        stroke-width="1"
                    />
                    <text
                        text-anchor="middle"
                        y="-10"
                        fill="#fff"
                        font-size="15"
                        font-weight="600"
                    >{{ $tooltipText }}</text>
                </g>
            </g>
        @endforeach

        @foreach ($circles as $point)
            <text
                class="chart-static"
                x="{{ $point['x'] }}"
                y="{{ $height - 52 }}"
                text-anchor="middle"
                fill="rgba(226,232,240,0.95)"
                font-size="14"
                font-weight="600"
            >
                <tspan x="{{ $point['x'] }}" dy="0">{{ $point['day_label'] }}</tspan>
                <tspan x="{{ $point['x'] }}" dy="18" fill="rgba(148,163,184,0.95)" font-size="13" font-weight="500">{{ $point['date_label'] }}</tspan>
            </text>
        @endforeach

        <text
            class="chart-static"
            x="{{ $padL + ($chartW / 2) }}"
            y="{{ $height - 8 }}"
            text-anchor="middle"
            fill="rgba(148,163,184,0.95)"
            font-size="14"
            font-weight="500"
        >Fecha</text>
    </svg>
</div>
@endif
