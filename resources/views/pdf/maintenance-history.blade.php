{{-- filepath: /home/ubuntu/Sites/sigem/resources/views/pdf/maintenance-history.blade.php --}}
<x-layout>
    <div style="text-align: center; margin-bottom: 20px; nargin-top: -60px;">
        <h3>Programa de Mantenimiento Vehicular</h3>
    </div>
    <div style="display: table; width: 100%;">
        <!-- Columna izquierda: Datos del vehículo -->
        <div style="display: table-cell; border: 1px solid #e0e0e0; padding: 12px; border-radius: 6px; background-color: #fdfdfd; vertical-align: top;">
            <div style="font-weight: 600; font-size: 14px; margin-bottom: 6px; color: #111; border-bottom: 1px solid #ddd; padding-bottom: 4px;">
                Vehículo
            </div>

            <div style="margin-bottom: 2px;"><strong>Placa:</strong> {{ $record->placa }}</div>
            <div style="margin-bottom: 2px;"><strong>Marca:</strong> {{ $record->marca ?? '-' }}</div>
            <div style="margin-bottom: 2px;"><strong>Unidad:</strong> {{ $record->unidad ?? '-' }}</div>
            <div style="margin-bottom: 2px;"><strong>Tarjeta Propiedad:</strong> {{ $record->property_card ?? '-' }}</div>


        </div>
    </div>
    <br>
    <div style="overflow-x: auto;">
        <table width="100%">
            <thead style="background-color: #f2f2f2;">
                <tr>
                    <th rowspan="2">#</th>
                    <th rowspan="2">Descripción</th>
                    @foreach ($mileages as $i => $km)
                    <th>{{ $i % 2 == 0 ? 'L' : 'M' }}</th>
                    @endforeach
                </tr>
                <tr>
                    @foreach ($mileages as $km)
                    <th>{{ number_format($km) }}</th>
                    @endforeach
                </tr>

            </thead>
            <tbody>
                @foreach ($maintenanceitems as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->name }}</td>
                    @foreach ($mileages as $km)
                    @php
                    $key = $item->id . '_' . $km;
                    $hasMaintenance = isset($maintenanceMap[$key]);
                    @endphp
                    <td style="padding: 6px; border: 1px solid #ddd; text-align: center;">
                        @if($hasMaintenance)
                        <span style="color: #636363ff; font-weight: bold;">X</span>
                        @endif
                    </td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-layout>
