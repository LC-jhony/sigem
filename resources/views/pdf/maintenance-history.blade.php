{{-- filepath: /home/ubuntu/Sites/sigem/resources/views/pdf/maintenance-history.blade.php --}}
<x-layout>
    <div style="text-align: center; margin-bottom: 20px;">
        <h1>PROGRAMA DE MANTENIMIENTO PREVENTIVO</h1>
        <p>Fecha: {{ now()->format('d/m/Y') }}</p>
    </div>
    <div style="margin-bottom: 10px;">
        <span style="font-weight: bold; font-size: 16px;">Vehículo:</span>
        <span style="font-size: 15px;">{{ $record->placa }}</span>
    </div>
    <div style="overflow-x: auto;">
        <table width="100%">
            <thead style="background-color: #f2f2f2;">
                <tr>
                    <th rowspan="2">#</th>
                    <th rowspan="2">Descripción</th>
                    @foreach ([7500,15000,22500,30000,37500,45000,52500,60000,67500,75000,82500,90000,97500,105000,112500,120000,127500,135000,142500,150000,157500,165000] as $i => $km)
                    <th>{{ $i % 2 == 0 ? 'L' : 'M' }}</th>
                    @endforeach
                </tr>
                <tr>
                    @foreach ([7500,15000,22500,30000,37500,45000,52500,60000,67500,75000,82500,90000,97500,105000,112500,120000,127500,135000,142500,150000,157500,165000] as $km)
                    <th>{{ number_format($km) }}</th>
                    @endforeach
                </tr>

            </thead>
            <tbody>
                @foreach ($maintenanceitems as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->name }}</td>
                    @foreach ([7500,15000,22500,30000,37500,45000,52500,60000,67500,75000,82500,90000,97500,105000,112500,120000,127500,135000,142500,150000,157500,165000] as $km)
                    @php
                    $hasMaintenance = $record->maintenances->where('maintenance_item_id', $item->id)->where('mileage', $km)->count() > 0;
                    @endphp
                    <td style="padding: 6px; border: 1px solid #ddd; text-align: center;">
                        @if($hasMaintenance)
                        <span style="color: green; font-weight: bold;">X</span>
                        @endif
                    </td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-layout>
