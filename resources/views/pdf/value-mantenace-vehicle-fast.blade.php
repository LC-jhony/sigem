<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Valor de Mantenimiento</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .info {
            background: #f9f9f9;
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
        }
        .info-item {
            margin-bottom: 8px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th {
            background: #4a4a4a;
            color: white;
            padding: 8px;
            text-align: center;
            border: 1px solid #333;
        }
        td {
            padding: 6px;
            border: 1px solid #ddd;
            text-align: center;
        }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .total-row {
            background: #e9e9e9;
            font-weight: bold;
        }
        .no-data {
            text-align: center;
            color: #666;
            padding: 40px;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Valor de Mantenimiento del Vehículo</h2>
    </div>

    <div class="info">
        <div class="info-item"><strong>Placa:</strong> {{ $record->placa ?? 'N/A' }}</div>
        <div class="info-item"><strong>Marca:</strong> {{ $record->marca ?? 'N/A' }}</div>
        <div class="info-item"><strong>Unidad:</strong> {{ $record->unidad ?? 'N/A' }}</div>
        <div class="info-item"><strong>Tarjeta de Propiedad:</strong> {{ $record->property_card ?? 'N/A' }}</div>
    </div>

    @if($record->maintenances && $record->maintenances->count() > 0)
        <table>
            <thead>
                <tr>
                    <th style="width: 40%;">Descripción</th>
                    <th style="width: 15%;">Kilometraje</th>
                    <th style="width: 15%;">Precio Material</th>
                    <th style="width: 15%;">Mano de Obra</th>
                    <th style="width: 15%;">Costo Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($record->maintenances as $maintenance)
                <tr>
                    <td class="text-left">
                        {{ $maintenance->maintenanceItem->name ?? 'Sin descripción' }}
                    </td>
                    <td>{{ number_format($maintenance->mileage ?? 0) }}</td>
                    <td class="text-right">S/. {{ number_format($maintenance->Price_material ?? 0, 2) }}</td>
                    <td class="text-right">S/. {{ number_format($maintenance->workforce ?? 0, 2) }}</td>
                    <td class="text-right">S/. {{ number_format($maintenance->maintenance_cost ?? 0, 2) }}</td>
                </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="4" class="text-right"><strong>TOTAL GENERAL:</strong></td>
                    <td class="text-right">
                        <strong>S/. {{ number_format($record->maintenances->sum('maintenance_cost'), 2) }}</strong>
                    </td>
                </tr>
            </tbody>
        </table>
    @else
        <div class="no-data">
            <p>No hay registros de mantenimiento para este vehículo.</p>
        </div>
    @endif

    @if($record->maintenances && $record->maintenances->count() > 0)
        @php
            $hasImages = false;
            foreach($record->maintenances as $maintenance) {
                if($maintenance->processed_photo || $maintenance->processed_file) {
                    $hasImages = true;
                    break;
                }
            }
        @endphp

        @if($hasImages)
            <div style="page-break-before: always; margin-top: 30px;">
                <h3 style="text-align: center; margin-bottom: 20px;">Imágenes de Mantenimiento</h3>

                @foreach($record->maintenances as $maintenance)
                    @if($maintenance->processed_photo)
                        <div style="text-align: center; margin-bottom: 20px;">
                            <img src="data:{{ $maintenance->processed_photo['mime'] }};base64,{{ $maintenance->processed_photo['data'] }}"
                                 style="max-width: 400px; max-height: 300px; border: 1px solid #ddd;">
                            <p style="font-size: 10px; color: #666; margin-top: 5px;">
                                {{ $maintenance->maintenanceItem->name ?? 'Mantenimiento' }} - KM: {{ number_format($maintenance->mileage ?? 0) }}
                            </p>
                        </div>
                    @endif

                    @if($maintenance->processed_file)
                        <div style="text-align: center; margin-bottom: 20px;">
                            <img src="data:{{ $maintenance->processed_file['mime'] }};base64,{{ $maintenance->processed_file['data'] }}"
                                 style="max-width: 400px; max-height: 300px; border: 1px solid #ddd;">
                            <p style="font-size: 10px; color: #666; margin-top: 5px;">
                                {{ $maintenance->maintenanceItem->name ?? 'Mantenimiento' }} - KM: {{ number_format($maintenance->mileage ?? 0) }}
                            </p>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif
    @endif
</body>
</html>
