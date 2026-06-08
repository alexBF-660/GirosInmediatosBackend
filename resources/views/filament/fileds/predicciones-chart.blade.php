@php
    $items = collect($get('predicciones') ?? []);
    $values = $items->pluck('prediccion_capital')->map(fn ($v) => (float) $v);
    $count = max($items->count(), 1);

    $minY = $values->min() ?? 0;
    $maxY = $values->max() ?? 0;
    $range = max($maxY - $minY, 1);
    $minY -= $range * 0.15;
    $maxY += $range * 0.15;
    $ySpan = max($maxY - $minY, 1);

    $width = 720;
    $height = 300;
    $padL = 72;
    $padR = 24;
    $padT = 24;
    $padB = 48;
    $chartW = $width - $padL - $padR;
    $chartH = $height - $padT - $padB;

    $points = [];
    $circles = [];

    foreach ($items as $index => $item) {
        $value = (float) ($item['prediccion_capital'] ?? 0);
        $x = $padL + ($count > 1 ? ($index / ($count - 1)) * $chartW : $chartW / 2);
        $y = $padT + $chartH - (($value - $minY) / $ySpan) * $chartH;
        $points[] = round($x, 1) . ',' . round($y, 1);
        $circles[] = ['x' => round($x, 1), 'y' => round($y, 1), 'value' => $value, 'fecha' => $item['fecha'] ?? '-'];
    }

    $polyline = implode(' ', $points);

    $gridLines = 5;
    $yLabels = [];
    for ($i = 0; $i <= $gridLines; $i++) {
        $value = $minY + ($ySpan / $gridLines) * $i;
        $y = $padT + $chartH - ($i / $gridLines) * $chartH;
        $yLabels[] = ['y' => round($y, 1), 'label' => number_format($value, 0, '.', ',')];
    }
@endphp

@if ($items->isEmpty())
    <p style="opacity:0.7; font-size:13px;">Seleccione una sucursal destino para ver la predicción.</p>
@else
<div style="width:100%;">
    <svg viewBox="0 0 {{ $width }} {{ $height }}" width="100%" height="{{ $height }}" style="max-width:100%; display:block;" role="img" aria-label="Gráfico de predicción de capital">
        @foreach ($yLabels as $line)
            <line x1="{{ $padL }}" y1="{{ $line['y'] }}" x2="{{ $width - $padR }}" y2="{{ $line['y'] }}" stroke="rgba(148,163,184,0.25)" stroke-width="1"/>
            <text x="{{ $padL - 8 }}" y="{{ $line['y'] + 4 }}" text-anchor="end" fill="rgba(148,163,184,0.9)" font-size="11">{{ $line['label'] }}</text>
        @endforeach

        <line x1="{{ $padL }}" y1="{{ $padT }}" x2="{{ $padL }}" y2="{{ $padT + $chartH }}" stroke="rgba(148,163,184,0.5)" stroke-width="1.5"/>
        <line x1="{{ $padL }}" y1="{{ $padT + $chartH }}" x2="{{ $width - $padR }}" y2="{{ $padT + $chartH }}" stroke="rgba(148,163,184,0.5)" stroke-width="1.5"/>

        @if ($minY < 0 && $maxY > 0)
            @php
                $zeroY = round($padT + $chartH - ((0 - $minY) / $ySpan) * $chartH, 1);
            @endphp
            <line x1="{{ $padL }}" y1="{{ $zeroY }}" x2="{{ $width - $padR }}" y2="{{ $zeroY }}" stroke="rgba(251,191,36,0.35)" stroke-width="1" stroke-dasharray="4,4"/>
        @endif

        @if (count($points) > 1)
            <polyline points="{{ $polyline }}" fill="none" stroke="#f59e0b" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
        @endif

        @foreach ($circles as $point)
            <circle cx="{{ $point['x'] }}" cy="{{ $point['y'] }}" r="5" fill="#f59e0b" stroke="#1f2937" stroke-width="2"/>
        @endforeach

        @foreach ($circles as $point)
            @php
                $label = \Illuminate\Support\Str::of($point['fecha'])->afterLast('-')->toString();
                if (strlen($label) === 0) {
                    $label = $point['fecha'];
                }
            @endphp
            <text x="{{ $point['x'] }}" y="{{ $height - 14 }}" text-anchor="middle" fill="rgba(148,163,184,0.95)" font-size="11">{{ $label }}</text>
        @endforeach

        <text x="{{ $padL - 46 }}" y="{{ $padT + ($chartH / 2) }}" text-anchor="middle" fill="rgba(148,163,184,0.9)" font-size="11" transform="rotate(-90 {{ $padL - 46 }} {{ $padT + ($chartH / 2) }})">Capital (Bs)</text>
        <text x="{{ $padL + ($chartW / 2) }}" y="{{ $height - 2 }}" text-anchor="middle" fill="rgba(148,163,184,0.9)" font-size="11">Fecha</text>
    </svg>
</div>
@endif
