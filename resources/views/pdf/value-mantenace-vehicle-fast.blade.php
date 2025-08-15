<x-layout>
    <x-slot name="head">
        <div class="report-title">
            <h1>VALORIZADO DE MANTENIMIENTO <br> VEHICULAR</h1>
            <div class="report-number">
                <strong style="font-widget: bold;"> Placa: </strong>
                {{ $record->placa }} <BR>
                <strong style="font-widget: bold;"> Marca: </strong>
                {{ $record->marca ?? '-' }} <BR>
                <strong style="font-widget: bold;"> Unidad: </strong>
                {{ $record->unidad ?? '-' }}< <BR>
                <strong style="font-widget: bold;"> Tarjeta Pro.: </strong>
                {{ $record->property_card ?? '-' }} <BR>
                {{-- <strong style="font-widget: bold;"> Mes: </strong>
                mes --}}
            </div>
        </div>
    </x-slot>
    @if($record->maintenances && $record->maintenances->count() > 0)
        <table class="table">
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
                                 style="width: 90%; max-width: 500px; border: 1px solid #ccc; padding: 5px;">
                            {{-- <p style="font-size: 10px; color: #666; margin-top: 5px;"> Foto:
                                {{ $maintenance->maintenanceItem->name ?? 'Mantenimiento' }} - KM: {{ number_format($maintenance->mileage ?? 0) }}
                            </p> --}}
                        </div>
                    @endif

                    @if($maintenance->processed_file)
                        <div style="text-align: center; margin-bottom: 20px;">
                            <img src="data:{{ $maintenance->processed_file['mime'] }};base64,{{ $maintenance->processed_file['data'] }}"
                                 style="width: 90%; max-width: 500px; border: 1px solid #ccc; padding: 5px;">
                            {{-- <p style="font-size: 10px; color: #666; margin-top: 5px;">
                                {{ $maintenance->maintenanceItem->name ?? 'Mantenimiento' }} - KM: {{ number_format($maintenance->mileage ?? 0) }}
                            </p> --}}
                        </div>
                    @endif
                @endforeach
            </div>
        @endif
    @endif
</x-layout>
