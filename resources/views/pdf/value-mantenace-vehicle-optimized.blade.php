<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Valor de Mantenimiento del Vehículo</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            line-height: 1.3;
            color: #333;
        }
        
        .header {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #333;
        }
        
        .header h3 {
            font-size: 16px;
            font-weight: bold;
            margin: 0;
        }
        
        .vehicle-info {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 3px;
        }
        
        .vehicle-info h4 {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 8px;
            color: #495057;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 3px;
        }
        
        .info-row {
            display: inline-block;
            width: 48%;
            margin-bottom: 3px;
            font-size: 10px;
        }
        
        .info-label {
            font-weight: bold;
            color: #495057;
        }
        
        .maintenance-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 9px;
        }
        
        .maintenance-table th {
            background: #6c757d;
            color: white;
            padding: 6px 4px;
            text-align: center;
            font-weight: bold;
            border: 1px solid #495057;
            font-size: 8px;
        }
        
        .maintenance-table td {
            padding: 4px;
            border: 1px solid #dee2e6;
            text-align: center;
            vertical-align: middle;
        }
        
        .maintenance-table tbody tr:nth-child(even) {
            background: #f8f9fa;
        }
        
        .maintenance-table tbody tr:hover {
            background: #e9ecef;
        }
        
        .text-right {
            text-align: right !important;
        }
        
        .text-left {
            text-align: left !important;
        }
        
        .total-row {
            background: #e9ecef !important;
            font-weight: bold;
        }
        
        .total-row td {
            border-top: 2px solid #495057;
        }
        
        .images-section {
            page-break-before: always;
            margin-top: 20px;
        }
        
        .image-container {
            text-align: center;
            margin-bottom: 15px;
            page-break-inside: avoid;
        }
        
        .maintenance-image {
            max-width: 400px;
            max-height: 300px;
            border: 1px solid #dee2e6;
            padding: 3px;
            background: white;
        }
        
        .no-images {
            text-align: center;
            color: #6c757d;
            font-style: italic;
            padding: 20px;
        }
        
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h3>Valor de Mantenimiento del Vehículo</h3>
    </div>

    <div class="vehicle-info">
        <h4>Información del Vehículo</h4>
        <div class="info-row">
            <span class="info-label">Placa:</span> {{ $record->placa }}
        </div>
        <div class="info-row">
            <span class="info-label">Marca:</span> {{ $record->marca ?? '-' }}
        </div>
        <div class="info-row">
            <span class="info-label">Unidad:</span> {{ $record->unidad ?? '-' }}
        </div>
        <div class="info-row">
            <span class="info-label">Tarjeta Propiedad:</span> {{ $record->property_card ?? '-' }}
        </div>
    </div>

    @if($record->maintenances->count() > 0)
        <table class="maintenance-table">
            <thead>
                <tr>
                    <th style="width: 35%;">Descripción</th>
                    <th style="width: 12%;">KM</th>
                    <th style="width: 17%;">Precio Material</th>
                    <th style="width: 16%;">Mano de Obra</th>
                    <th style="width: 20%;">Costo Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($record->maintenances as $item)
                <tr>
                    <td class="text-left">{{ $item->maintenanceItem->name ?? 'N/A' }}</td>
                    <td>{{ number_format($item->mileage) }}</td>
                    <td class="text-right">S/. {{ number_format($item->Price_material, 2) }}</td>
                    <td class="text-right">S/. {{ number_format($item->workforce, 2) }}</td>
                    <td class="text-right">S/. {{ number_format($item->maintenance_cost, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="4" class="text-right"><strong>Total General:</strong></td>
                    <td class="text-right"><strong>S/. {{ number_format($record->maintenances->sum('maintenance_cost'), 2) }}</strong></td>
                </tr>
            </tfoot>
        </table>

        @php
            $hasImages = $record->maintenances->filter(function($item) {
                return $item->processed_photo || $item->processed_file;
            })->count() > 0;
        @endphp

        @if($hasImages)
            <div class="images-section">
                <h4 style="text-align: center; margin-bottom: 15px; color: #495057;">Imágenes de Mantenimiento</h4>
                
                @foreach($record->maintenances as $item)
                    @if(isset($item->processed_photo) && $item->processed_photo)
                        <div class="image-container">
                            <img src="data:{{ $item->processed_photo['mime'] }};base64,{{ $item->processed_photo['data'] }}"
                                 class="maintenance-image"
                                 alt="Foto de mantenimiento">
                            <p style="font-size: 8px; color: #6c757d; margin-top: 5px;">
                                {{ $item->maintenanceItem->name ?? 'Mantenimiento' }} - KM: {{ number_format($item->mileage) }}
                            </p>
                        </div>
                    @endif

                    @if(isset($item->processed_file) && $item->processed_file)
                        <div class="image-container">
                            <img src="data:{{ $item->processed_file['mime'] }};base64,{{ $item->processed_file['data'] }}"
                                 class="maintenance-image"
                                 alt="Archivo de mantenimiento">
                            <p style="font-size: 8px; color: #6c757d; margin-top: 5px;">
                                {{ $item->maintenanceItem->name ?? 'Mantenimiento' }} - KM: {{ number_format($item->mileage) }}
                            </p>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif
    @else
        <div class="no-images">
            <p>No hay registros de mantenimiento para este vehículo.</p>
        </div>
    @endif
</body>
</html>
