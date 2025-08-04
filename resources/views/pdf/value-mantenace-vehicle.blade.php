<x-layout>
    <div style="text-align: center; margin-bottom: 20px; margin-top: -60px;">
        <h3>Valor de Mantenimiento del Vehículo</h3>
    </div>
    <div style="display: table; width: 100%;">
        <!-- Columna izquierda: Datos del vehículo -->
        <div
            style="display: table-cell; border: 1px solid #e0e0e0; padding: 12px; border-radius: 6px; background-color: #fdfdfd; vertical-align: top;">
            <div
                style="font-weight: 600; font-size: 14px; margin-bottom: 6px; color: #111; border-bottom: 1px solid #ddd; padding-bottom: 4px;">
                Vehículo
            </div>
            <div style="margin-bottom: 2px;"><strong>Placa:</strong> {{ $record->placa }}</div>
            <div style="margin-bottom: 2px;"><strong>Marca:</strong> {{ $record->marca ?? '-' }}</div>
            <div style="margin-bottom: 2px;"><strong>Unidad:</strong> {{ $record->unidad ?? '-' }}</div>
            <div style="margin-bottom: 2px;"><strong>Tarjeta Propiedad:</strong> {{ $record->property_card ?? '-' }}
            </div>
        </div>
    </div>
    <br>

    <table width="100%">
        <thead style="background-color: lightgray; font-size: 12px;">
            <tr>
                <th>Descripcion</th>
                <th>Kilometraje</th>
                <th>Precio Material</th>
                <th>Mano de Obra</th>
                <th>Costo Mantenimiento</th>
            </tr>
        </thead>
        <tbody>
            @foreach($record->maintenances as $item)
            <tr>
                <td>{{ $item->maintenanceItem->name }}</td>
                <td>{{ $item->mileage }}</td>
                <td style="text-align: right;">{{ $item->Price_material }}</td>
                <td style="text-align: right;">{{ $item->workforce }}</td>
                <td style="text-align: right;">{{ $item->maintenance_cost }}</td>
            </tr>
            @endforeach
        </tbody>
        @php
        $total = $record->maintenances->sum('maintenance_cost');
        @endphp
        <tfoot>
            <tr>
                <td colspan="4" style="text-align: right;"><strong>Total general:</strong></td>
                <td style="text-align: right;"><strong>S/. {{ number_format($total , 2) }}</strong></td>
            </tr>
        </tfoot>
    </table>
    
    <!-- Salto de página para las imágenes -->
    <div style="page-break-before: always;"></div>
    
    <!-- Contenedor para las imágenes que ocupen toda la página -->
    <div style="width: 100%; text-align: center;">

@foreach($record->maintenances as $item)
    @if($item->photo)
        @php
            $photoPath = storage_path('app/public/' . $item->photo);
        @endphp
        @if(file_exists($photoPath) && is_readable($photoPath))
            <div style="margin-bottom: 20px;">
                @php
                    $photoMime = mime_content_type($photoPath);
                @endphp
                <img src="data:{{ $photoMime }};base64,{{ base64_encode(file_get_contents($photoPath)) }}"
                     style="width: 90%; max-width: 500px; border: 1px solid #ccc; padding: 5px;">
            </div>
        @endif
    @endif

    @if($item->file)
        @php
            $filePath = storage_path('app/public/' . $item->file);
        @endphp
        @if(file_exists($filePath) && is_readable($filePath))
            <div style="margin-bottom: 20px;">
                @php
                    $fileMime = mime_content_type($filePath);
                @endphp
                <img src="data:{{ $fileMime }};base64,{{ base64_encode(file_get_contents($filePath)) }}"
                     style="width: 90%; max-width: 500px; border: 1px solid #ccc; padding: 5px;">
            </div>
        @endif
    @endif
@endforeach

</div>

</x-layout>