<x-layout>

    <div style="display: table; width: 100%;">

        <!-- Columna izquierda: Datos del vehículo -->
        <div style="display: table-cell; width: 49%; border: 1px solid #e0e0e0; padding: 12px; border-radius: 6px; background-color: #f9f9f9; vertical-align: top;">
            <div style="font-weight: 600; font-size: 14px; margin-bottom: 6px; color: #111;">Vehículo</div>
            <div><strong>Placa:</strong> {{ $record->placa }}</div>
            <div><strong>Marca:</strong> {{ $record->marca ?? '-' }}</div>
            <div><strong>Unidad:</strong> {{ $record->unidad ?? '-' }}</div>
            <div><strong>Tarjeta Propiedad:</strong> {{ $record->property_card ?? '-' }}</div>
            <div><strong>Fecha de reporte:</strong> {{ date('d/m/Y') }}</div>
        </div>

        <!-- Separador invisible -->
        <div style="display: table-cell; width: 2%;"></div>

        <!-- Columna derecha: Documentos -->
        <div style="display: table-cell; width: 49%; border: 1px solid #e0e0e0; padding: 12px; border-radius: 6px; background-color: #fdfdfd; vertical-align: top;">
            <div style="font-weight: 600; font-size: 14px; margin-bottom: 6px; color: #111; border-bottom: 1px solid #ddd; padding-bottom: 4px;">
                Documentos del vehículo
            </div>

            @forelse($record->documents as $document)
            <div style="margin-bottom: 10px; padding-bottom: 6px; border-bottom: 1px dashed #ccc;">
                <div style="margin-bottom: 2px;"><strong>{{ $document->type }}</strong>: {{ $document->name ?? 'Sin nombre' }}</div>
                <div style="color: #666;">Fecha: {{ \Carbon\Carbon::parse($document->date)->format('d/m/Y') }}</div>
            </div>
            @empty
            <div style="color: #888;">No hay documentos registrados.</div>
            @endforelse
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
                <td style="  text-align: right;">{{ $item->Price_material }}</td>
                <td style="  text-align: right;">{{ $item->workforce }}</td>
                <td style="  text-align: right;">{{ $item->maintenance_cost }}</td>
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
</x-layout>
