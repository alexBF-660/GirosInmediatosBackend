@php
    $predicciones = $get('predicciones');
@endphp

@if ($predicciones)
    <div class="p-4 border rounded-lg bg-white shadow mt-3">
        <h3 class="text-lg font-semibold text-gray-700 mb-3">📊 Predicción de Capital</h3>

        <table class="min-w-full text-sm text-gray-600">
            <thead class="border-b font-medium">
                <tr>
                    <th class="px-3 py-2 text-left">Fecha</th>
                    <th class="px-3 py-2 text-left">Predicción Capital</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($predicciones as $item)
                    <tr class="border-b">
                        <td class="px-3 py-1">{{ $item['fecha'] ?? '-' }}</td>
                        <td class="px-3 py-1">{{ number_format($item['prediccion_capital'] ?? 0, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
